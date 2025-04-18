FROM composer:latest

ENV COMPOSERUSER=restaurant
ENV COMPOSERGROUP=restaurant

RUN adduser -g ${COMPOSERGROUP} -s /bin/sh -D ${COMPOSERUSER}