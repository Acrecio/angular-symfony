FROM node:5

ADD . /var/www
WORKDIR /var/www

RUN npm install -g gulp-cli \
  && rm -rf node_modules && rm -rf dist \
  && npm install

EXPOSE 3000
CMD ["npm", "start"]
