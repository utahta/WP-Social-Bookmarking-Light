.PHONY: install
install:
	composer install

.PHONY: build
build:
	@rm -rf vendor build
	@mkdir build
	@git archive --worktree-attributes --format=tar --prefix=wp-social-bookmarking-light/ HEAD | (cd build && tar xf -)
	@composer install --no-dev
	@cp -r ./vendor ./build/wp-social-bookmarking-light/vendor

.PHONY: test
test:
	./vendor/bin/phpunit src

docker/up: build
	@make -s -C ./dockerfiles up

docker/stop:
	@make -s -C ./dockerfiles stop

docker/up-wp47-php56: build
	@make -s -C ./dockerfiles up-wp47-php56

docker/stop-wp47-php56:
	@make -s -C ./dockerfiles stop-wp47-php56

docker/up-wp47-php71: build
	@make -s -C ./dockerfiles up-wp47-php71

docker/stop-wp47-php71:
	@make -s -C ./dockerfiles stop-wp47-php71

docker/up-wp46-php56: build
	@make -s -C ./dockerfiles up-wp46-php56

docker/stop-wp46-php56:
	@make -s -C ./dockerfiles stop-wp46-php56

docker/up-wp48-php53:
	@make -s -C ./dockerfiles/php53 up

docker/stop-wp48-php53:
	@make -s -C ./dockerfiles/php53 stop

