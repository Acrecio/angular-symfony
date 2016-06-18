FROM node:5

ADD . /var/www
WORKDIR /var/www

RUN npm install
RUN npm install -g gulp

EXPOSE 3000
CMD ["npm", "start"]
