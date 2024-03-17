FROM php:7.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libsqlite3-dev \
    unzip \
    git \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Check versions
RUN node --version && npm --version

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
