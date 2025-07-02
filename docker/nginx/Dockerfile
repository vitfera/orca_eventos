FROM nginx:1.27-alpine

# Copiar a configuração customizada do nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copiar os arquivos da aplicação
COPY backend/public /var/www/public

# Definir as permissões corretas
RUN chown -R nginx:nginx /var/www/public

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
