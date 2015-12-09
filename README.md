# quiz-helper

Backend for https://github.com/kriku/helper

## Installation

1. download and install composer from https://getcomposer.org/download/ 
    ```
    curl -sS https://getcomposer.org/installer | php
    ```
2. `> composer install`
3. start web server

## input format
POST 
```
data={"question":"TEST", "answers": ["ans1", "ans2"]}
```
returns either answer or empty line (adds question to answer queue)
