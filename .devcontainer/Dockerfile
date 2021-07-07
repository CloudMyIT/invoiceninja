FROM laravelphp/vapor:php74

RUN apk add git

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN apk add chromium npm docker sudo

RUN sed -e 's/# %wheel ALL=(ALL) NOPASSWD: ALL/%wheel ALL=(ALL) NOPASSWD: ALL/g' \
      -i /etc/sudoers

RUN adduser -D dev \
    && addgroup dev wheel \
    && addgroup dev docker

USER dev
