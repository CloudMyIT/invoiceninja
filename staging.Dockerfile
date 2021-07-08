FROM laravelphp/vapor:php80

RUN apk add chromium

COPY . /var/task