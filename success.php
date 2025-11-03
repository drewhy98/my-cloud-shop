 <! DOCTYPE html >
 <html lang ="en">
 <head >
 <meta charset ="UTF -8">
 <meta name =" viewport " content =" width =device -width , initial - scale
=1.0 ">
 <title > Registration Successful - MyShop </ title >
 <style >
 body {
 font - family : Arial , sans - serif ;
 background - color : # f4f4f4 ;
 display : flex ;
 justify - content : center ;
 align - items : center ;
 height : 100 vh ;
 margin : 0;
 text - align : center ;
 }
 . header {
 background - color : #2 c3e50 ;
 color : white ;
 padding : 20 px 0;
 text - align : center ;
 position : fixed ;
 top : 0;
 width : 100%;
 }
 . success - message {
 background : white ;
 padding : 40 px ;
 border - radius : 8 px ;
 box - shadow : 0 0 10 px rgba (0 ,0 ,0 ,0.1) ;
 max - width : 500 px ;
 margin - top : 60 px ;
 }
 . success - icon {
 font - size : 48 px ;
 color : #28 a745 ;
 margin - bottom : 20 px ;
 }
 . btn {
 display : inline - block ;
 background - color : #007 bff ;
 color : white ;
 padding : 10 px 20 px ;
 text - decoration : none ;
 border - radius : 4 px ;
 margin : 10 px ;
 }
 . btn : hover {
 background - color : #0056 b3 ;
 }
 . btn - success {
 background - color : #28 a745 ;
 }
 . btn - success : hover {
 background - color : #218838;
 }
 </ style >
 </ head >
 <body >
 <div class =" header ">
 <h1 > MyShop </h1 >
 <p> Registration Successful </p>
 </div >

 <div class =" success - message ">
 <div class =" success - icon "> </div >

 <h2 > Registration Successful ! </h2 >
 <p> Your account has been created successfully . Welcome to
MyShop ! </p>

 <div style ="margin -top : 30 px;">
 <a href =" index .php " class ="btn "> Go Back to Home </a>
 <a href =" register .php " class ="btn btn - success "> Register
Another User </a>
 </div >
 </div >
 </ body >
 </ html >