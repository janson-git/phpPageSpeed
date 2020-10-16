# phpPageSpeed

Open page in Chrome browser, open DevTools -> Network

Right-click on request and get Copy -> Copy as CURL

Then paste clipboard to speedpage.php as content of `$curlString` variable.

And run:
```
$ php speedpage.php 
URL: https://www.google.com/
Lets go 5 times
.....
Average total time: 0.135702
Max total time: 0.187782
Min total time: 0.107746
```

