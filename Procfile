worker: php index.php
git init
git add .
git commit -m "Initial commit"
heroku login
heroku create sdagfd
heroku config:set BOT_TOKEN=6022228026:AAH4MwFk3sNno832jZ9SorCsVbHBheK2c-0
git push heroku master
heroku ps:scale worker=1
