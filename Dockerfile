FROM php:8.2-fpm

ARG user=laravel
ARG uid=1000

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        zip \
        curl \
        rsync \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

WORKDIR /var/www/html

COPY scripts/setup.sh /usr/local/bin/project-setup
RUN chmod +x /usr/local/bin/project-setup

USER $user

# Ensure php-fpm is found when running as a non-root user (sbin not in PATH)
CMD ["bash", "-lc", "project-setup && exec /usr/local/sbin/php-fpm -F"]
