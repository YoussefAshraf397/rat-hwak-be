<?php

namespace App\Controller;

use App\Dto\Payment\InitPaymentPageRequest;
use App\Dto\Payment\InitPaymentPageResponse;
use App\Dto\Payment\SiteRequest;
use App\Entity\Payment;
use App\Notify\TelegramNotifier;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use JsonMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    protected const BASE_URL = 'https://alfa.rbsuat.com/payment/rest/';

    protected const INIT_DEPOSIT = 'register.do';

    protected const SUCCESSFUL_DEPOSIT = 0;

    protected const NOTIFY_ALERT_TEMPLATE = 'Alfabank payment api responds: %s, code: %s';

    protected Client $apiClient;

    protected TelegramNotifier $telegramNotifier;

    protected JsonMapper $jsonMapper;

    protected EntityManagerInterface $entityManager;

    public function __construct(TelegramNotifier $telegramNotifier, EntityManagerInterface $entityManager)
    {
        $this->apiClient = new Client();
        $this->telegramNotifier = $telegramNotifier;
        $this->jsonMapper = new JsonMapper();
        $this->entityManager = $entityManager;
    }

    #[Route('/payment', name: 'app_payment', methods: ['POST'])]
    public function index(Request $rawRequest): JsonResponse
    {
        $siteRequest = $this->jsonMapper->map(json_decode($rawRequest->getContent()), new SiteRequest());

        $payment = (new Payment())
            ->setCreatedAt(new \DateTime())
            ->setAmount($siteRequest->getAmount());
        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $request = (new InitPaymentPageRequest())
            ->setUserName(getenv('ALFABANK_LOGIN'))
            ->setPassword(getenv('ALFABANK_PASSWORD'))
            ->setOrderNumber($payment->getId())
            //->setReturnUrl('http://hr.rebooking.global/return')
            ->setReturnUrl($siteRequest->getReturnUrl())
            //->setAmount(1000)
            ->setAmount($siteRequest->getAmount())
        ;

        parse_str(http_build_query($request), $temp);

        $res = $this->apiClient
            ->post($this->getUrl(static::INIT_DEPOSIT), ['form_params' => $temp])
            ->getBody()
            ->getContents();

        $response = $this->jsonMapper->map(json_decode($res), new InitPaymentPageResponse());
        /**
         * @var $response InitPaymentPageResponse
         */
        if (static::SUCCESSFUL_DEPOSIT !== $response->getErrorCode()) {
            $this
                ->telegramNotifier
                ->notify(sprintf(
                    static::NOTIFY_ALERT_TEMPLATE,
                    $response->getErrorMessage(),
                    $response->getErrorCode()));
            $payment->setErrorCode($response->getErrorCode());
            $this->entityManager->persist($payment);
            $this->entityManager->flush();

            return $this->json([
                'success' => false,
                'message' => 'payment api error'
            ], Response::HTTP_BAD_REQUEST);
        }


        $payment
            ->setErrorCode($response->getErrorCode())
            ->setTransactionId($response->getOrderId());
        $this->entityManager->persist($payment);
        $this->entityManager->flush();


        return $this->json([
            'success' => true,
            'data' => [
                'alfabank_order_id' => $response->getOrderId(),
                'payment_page_url' => $response->getFormUrl(),
                'internal_order_id' => $payment->getId(),
            ],
        ]);
    }

    protected function getUrl(string $method): string
    {
        return static::BASE_URL . $method;
    }
}
