FROM yiisoftware/yii2-php:8.2-apache

WORKDIR /app

COPY --chown=www-data:www-data . /app

RUN apt-get update && apt-get install -y supervisor \
    && rm -rf /var/lib/apt/lists/* \
    && composer install

COPY ./.docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
