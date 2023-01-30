<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <link rel="icon" href="../assets/quick/favicon.ico"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta name="theme-color" content="#000000"/>
    <meta name="description" content="Web site created using create-react-app"/>
    <link rel="apple-touch-icon" href="../assets/quick/logo192.png"/>
    <link rel="manifest" href="../assets/quick/manifest.json"/>
    
    <title>Laporan eJKP</title>
    
    <script>
        var Report = {
          service: {
            url: '<?php echo $url ?>',
            params: <?php echo json_encode($searchModel) ?>,
          },
        }
    </script>
    
    <link href="../assets/quick/static/css/main.5602de50.chunk.css" rel="stylesheet">
  </head>
  
  <body><noscript>You need to enable JavaScript to run this app.</noscript>
  
  <div id="root"></div>
    <script>!function(e){function r(r){for(var n,i,p=r[0],a=r[1],l=r[2],c=0,s=[];c<p.length;c++)i=p[c],Object.prototype.hasOwnProperty.call(o,i)&&o[i]&&s.push(o[i][0]),o[i]=0;for(n in a)Object.prototype.hasOwnProperty.call(a,n)&&(e[n]=a[n]);for(f&&f(r);s.length;)s.shift()();return u.push.apply(u,l||[]),t()}function t(){for(var e,r=0;r<u.length;r++){for(var t=u[r],n=!0,p=1;p<t.length;p++){var a=t[p];0!==o[a]&&(n=!1)}n&&(u.splice(r--,1),e=i(i.s=t[0]))}return e}var n={},o={1:0},u=[];function i(r){if(n[r])return n[r].exports;var t=n[r]={i:r,l:!1,exports:{}};return e[r].call(t.exports,t,t.exports,i),t.l=!0,t.exports}i.m=e,i.c=n,i.d=function(e,r,t){i.o(e,r)||Object.defineProperty(e,r,{enumerable:!0,get:t})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,r){if(1&r&&(e=i(e)),8&r)return e;if(4&r&&"object"==typeof e&&e&&e.__esModule)return e;var t=Object.create(null);if(i.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:e}),2&r&&"string"!=typeof e)for(var n in e)i.d(t,n,function(r){return e[r]}.bind(null,n));return t},i.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(r,"a",r),r},i.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},i.p="../assets/quick/";var p=this["webpackJsonpiems-report-page"]=this["webpackJsonpiems-report-page"]||[],a=p.push.bind(p);p.push=r,p=p.slice();for(var l=0;l<p.length;l++)r(p[l]);var f=a;t()}([])</script><script src="../assets/quick/static/js/2.f9c93a41.chunk.js"></script><script src="../assets/quick/static/js/main.d0464145.chunk.js"></script></body></html>
<?php exit(); ?>
