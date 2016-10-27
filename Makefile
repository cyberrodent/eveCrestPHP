.DEFAULT: test
.PHONY: test

SHELL = /bin/sh
PUXML = ${PWD}/test/phpunit.xml
PHP = php
PU = ${PWD}/vendor/bin/phpunit -c ${PUXML} ${PWD}/test/*.php

test:
	@echo Running PHPUnit Tests
	${PU}



