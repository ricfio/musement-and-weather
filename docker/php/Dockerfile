ARG PHP_VERSION=8.0.6

###############################
# base target
###############################
FROM php:${PHP_VERSION}-fpm-alpine AS base

# Working directory
WORKDIR /app

# Use the default development configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# INSTALL: composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

###############################
# development target
###############################
FROM base AS development

# INSTALL: bash, git, openssh, tzdata, gettext
RUN apk add --no-cache bash git openssh tzdata gettext

# INSTALL: Xdebug
RUN apk --no-cache add $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

# CONFIG: Xdebug
ARG XDEBUG_MODE=off
ARG XDEBUG_CLIENT_PORT=9003
ARG XDEBUG_CLIENT_HOST=host.docker.internal
COPY templates/docker-php-ext-xdebug.ini.template "$PHP_INI_DIR/conf.d/"
RUN export XDEBUG_MODE=$XDEBUG_MODE && \
    export XDEBUG_CLIENT_PORT=$XDEBUG_CLIENT_PORT && \
    export XDEBUG_CLIENT_HOST=$XDEBUG_CLIENT_HOST && \
    envsubst < "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini.template" > "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" && \
    rm -f "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini.template"

# INSTALL: symfony CLI
COPY --from=symfonycorp/cli /symfony /usr/bin/symfony

# CONFIG: timezone
ARG PHP_SYSTEM_TZ=Europe/London
ENV TZ=$PHP_SYSTEM_TZ

# CUSTOMIZE: shell
RUN echo "export PS1='🐳 [\[\033[1;36m\]\h \[\033[1;34m\]\W\[\033[0m\]]\[\033[1;36m\]# \[\033[0m\]'" >> ~/.bashrc
SHELL ["/bin/bash"]

###############################
# test target
###############################
FROM development AS test

RUN docker-php-ext-disable xdebug
