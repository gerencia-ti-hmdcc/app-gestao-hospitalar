docker build -t gerenciatihmdcc/hmdcc:app-gestao-hospitalar .
docker push gerenciatihmdcc/hmdcc:app-gestao-hospitalar


docker run -d -p 80:80 -e MYSQL_HOST='' -e MYSQL_DATABASE='' -e MYSQL_USERNAME='hmdcc_root' -e MYSQL_PASSWORD='' -e CI_ENVIRONMENT='development' -e DB_DEFAULT_GROUP='minasonline' -e CI_BASE_URL='http://hmdcc-lap3020/'  --name app-hmdcc gerenciatihmdcc/hmdcc:app-gestao-hospitalar

docker run -d -p 80:80 --name app-hmdcc gerenciatihmdcc/hmdcc:app-gestao-hospitalar-v1

 CI_ENVIRONMENT = development
 DEFAULT_GROUP = default