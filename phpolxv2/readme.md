App made to resolve the follow technical test:

Provide an api to handle users entities. The api should allow the user to:
- Get his information
- Delete his profile
- Change his profile data
- Upload an image
-- To upload an image, use this resource (on any php library of your choose): 
$ curl -XPOST "https://api.olx.com/v1.0/users/images" -F "file=@img.jpg"
{"id":1956350,"url":"ui/50/97/16/64/o_1471879056_200d9c0e4f5c597d7b12d07938de58df.jpg"}
Just prepend https://images01.olx-st.com/ to the returned url to be able to access the image.

User's table:
id int(11) not null primary key auto_increment,
name varchar(100) not null,
picture varchar(200),
address text

- Rules:
-- You can use any Module you want, but you can't use a framework (Example: you could use symfony/dependency-injection, bou you can't use symfony as a whole)

- Bonus points:
-- Unit tests
-- Code Style (You could choose any Standard, as long as you respect it, just let us know which)
-- Provide a docker env to test your code
-- Improvement ideas / feedback

---

Before start containers, perform the following tasks:
* copy .env.sample to .env and modify variables according
* copy .env-docker.sample to .env-docker and modify variables according

To start containers run:
$ docker-compose up

To start phpunit container and run tests:
$ ./docker-phpunit.sh
or
$ docker run --link olx-nginx:nginx --net phpolxv2_default -v $(pwd):/var/www/html --rm phpunit/phpunit -c /var/www/html/phpunit-docker.xml
Note: If the containers has never been started, this will fail if the containers don't start for the first time and insert test data to database