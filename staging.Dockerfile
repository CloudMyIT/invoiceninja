FROM laravelphp/vapor:php80

RUN apk add chromium nss

COPY . /var/task