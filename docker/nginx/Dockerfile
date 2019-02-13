FROM nginx:alpine

COPY docker/nginx/site.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD "nginx" "-g" "daemon off;"