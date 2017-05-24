FROM codemix/yii2-base:2.0.11.2-php7-fpm

# Install Additional PHP extensions
RUN apt-get update \
    && apt-get -y install \
            libfreetype6-dev \
            libjpeg62-turbo-dev \
            libmcrypt-dev \
            libpng12-dev \
            libxslt-dev \
        --no-install-recommends \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-install iconv mcrypt xsl \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

# Composer packages are installed first. This will only add packages
# that are not already in the yii2-base image
COPY composer.json /var/www/html/
COPY composer.lock /var/www/html/
RUN composer self-update --no-progress && \
    composer install --no-progress

# Copy the working dir to the image's web root
COPY . /var/www/html


# The following directories are .dockerignored to not pollute the docker images
# with local logs and published assets from development. So we need to create
# empty dirs and set right permissions inside the container.ADD

RUN mkdir app/runtime app/web/assets \
    && chown www-data:www-data app/runtime app/web/assets

# Expose everything under the /var/www (vendor + html)
# This is only required for the nginx setup
VOLUME ["/var/www"]