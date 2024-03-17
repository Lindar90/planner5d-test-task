FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    curl \
    libsqlite3-dev \
    unzip

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

RUN node --version && npm --version

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
