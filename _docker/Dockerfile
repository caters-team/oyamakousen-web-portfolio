FROM php:8.3-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install system dependencies and msmtp
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    msmtp \
    msmtp-mta \
    && docker-php-ext-install zip pdo pdo_mysql mysqli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure msmtp for Mailpit
RUN echo "account default" > /etc/msmtprc && \
    echo "host mailpit" >> /etc/msmtprc && \
    echo "port 1025" >> /etc/msmtprc && \
    echo "from noreply@example.com" >> /etc/msmtprc && \
    echo "auth off" >> /etc/msmtprc && \
    echo "tls off" >> /etc/msmtprc && \
    chmod 644 /etc/msmtprc

# Configure PHP to use msmtp
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini

# Set working directory
WORKDIR /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html
