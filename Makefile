PHP := php

insert-reviews:
	$(PHP) bin/console app:insert-reviews

delta:
	$(PHP) bin/console app:make-delta
