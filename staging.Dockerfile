FROM laravelphp/vapor:php80

RUN apk add chromium nodejs npm

COPY . /var/task