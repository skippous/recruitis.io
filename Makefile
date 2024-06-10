all:
	@awk '{ \
			if ($$0 ~ /^.PHONY: [a-zA-Z\-\_0-9]+$$/) { \
				helpCommand = substr($$0, index($$0, ":") + 2); \
				if (helpMessage) { \
					printf "\033[36m%-28s\033[0m %s\n", \
						helpCommand, helpMessage; \
					helpMessage = ""; \
				} \
			} else if ($$0 ~ /^[a-zA-Z\-\_0-9.]+:/) { \
				helpCommand = substr($$0, 0, index($$0, ":")); \
				if (helpMessage) { \
					printf "\033[36m%-20s\033[0m %s\n", \
						helpCommand, helpMessage; \
					helpMessage = ""; \
				} \
			} else if ($$0 ~ /^##/) { \
				if (helpMessage) { \
					helpMessage = helpMessage"\n                     "substr($$0, 3); \
				} else { \
					helpMessage = substr($$0, 3); \
				} \
			} else { \
				if (helpMessage) { \
					print "\n                     "helpMessage"\n" \
				} \
				helpMessage = ""; \
			} \
		}' \
		$(MAKEFILE_LIST)

## QA Stack
.PHONY: qa
qa: cs phpstan tests

## CodeSniffer - checks codestyle and typehints
.PHONY: cs
cs:
	@vendor/bin/phpcs --cache=var/phpcs.cache --standard=qa/ruleset.xml --extensions=php --tab-width=4 -sp app tests

## CodeSniffer - checks codestyle and typehints
.PHONY: cs-fix
cs-fix:
	@vendor/bin/phpcbf --standard=qa/ruleset.xml --extensions=php --tab-width=4 -sp app tests

## PhpStan - PHP Static Analysis
.PHONY: phpstan
phpstan:
	@vendor/bin/phpstan analyse --memory-limit=1024M -c qa/phpstan.neon --ansi --verbose app tests

## PhpUnit tests
.PHONY: tests
tests:
	@bin/phpunit

## webpack build dev
.PHONY: build
build:
	@npm run dev

## Print this help
.PHONY: help
help:	all
