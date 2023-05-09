# flag-classification

A <a href = 'https://github.com/jorgecasas/php-ml'>PHP-ML</a> model for national flag image classification.

- requirements: php8+, php-gd
- input: 40 * 30 grayscale image

## classes

<table>
<tr><td>0</td><td>South Korea</td><td>Taegukgi</td></tr>
<tr><td>1</td><td>North Korea</td><td>Ramhongsaek Konghwagukgi</td></tr>
<tr><td>2</td><td>United States</td><td>The Stars and Stripes</td></tr>
<tr><td>3</td><td>Japan</td><td>Nisshoki</td></tr>
<tr><td>4</td><td>China</td><td>Wu Xing Hong Qi</td></tr>
</table>

## prediction
```
php -f predict.php
```
