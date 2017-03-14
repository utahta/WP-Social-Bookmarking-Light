.PHONY: install
install:
	composer install

.PHONY: test
test:
	./vendor/bin/phpunit src

docker/up47:
	@make -s -C ./dockerfiles up47

docker/stop47:
	@make -s -C ./dockerfiles stop47

docker/up46:
	@make -s -C ./dockerfiles up46

docker/stop46:
	@make -s -C ./dockerfiles stop46

