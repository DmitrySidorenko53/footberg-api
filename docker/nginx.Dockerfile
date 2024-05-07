FROM nginx

ADD docker/conf.d/nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/laravel-docker
