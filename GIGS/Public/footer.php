<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon/Picture4.ico"/>
    <title></title>    
    <link rel="stylesheet" type="text/css" href="../css/navBar.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">

    <script src="js/navBar.js"></script>


</head>
<style>

@import url('https://fonts.googleapis.com/css2?family=BioRhyme+Expanded:wght@300;700&family=Courgette&display=swap');

body{
    margin: 0;
    font-family: 'Courgette', Arial, Helvetica, sans-serif;
}

.container{
	max-width: 1170px;
	margin: auto;
}

.row{
	display: flex;
	flex-wrap: wrap;
}

ul{
	list-style: none;
}

footer{
    margin-top:5%;
	background-color: #243b76;
    padding: 20px 0;
}

.footer-col{
   width: 25%;
   padding: 0 15px;
   margin-left: 235px;
}

.footer-col h4{
	font-size: 18px;
	color: #97D779;
	text-transform: capitalize;
	margin-bottom: 35px;
	font-weight: 800;
	position: relative;
}

.footer-col h4::before{
	content: '';
	position: absolute;
	left:0;
	bottom: -10px;
	background-color: #48BEC5;
	height: 2px;
	box-sizing: border-box;
	width: 50px;
}

.footer-col ul li:not(:last-child){
	margin-bottom: 10px;
}

.footer-col ul li a{
	font-size: 16px;
	text-transform: capitalize;
	color: #ffffff;
	text-decoration: none;
	font-weight: 700;
	color: #48BEC5;
	display: block;
	transition: all 0.3s ease;
}

.footer-col ul li a:hover{
	color: #F0F1B7;
	padding-left: 8px;
}

.footer-logo{
    margin-left: 440px;
}


/*responsive*/
@media(max-width: 767px){
  .footer-col{
    width: 50%;
    margin-bottom: 30px;
    }
}

@media(max-width: 574px){
  .footer-col{
    width: 100%;
    }
}


</style>
<body>

    <footer>
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="about.php">About us</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Get Help</h4>
                    <ul>
                        <li><a href="contact.php">Contact Us</a></li> 
                    </ul>
                </div>
                <div class="footer-logo">                    
                    <ul>
                        <li><a href="./firstpage.php"><img src="../icon/FLEXYGIG_for light background.png" width=160 height=40></a></div></li>                        
                    </ul>
                </div>

            </div>
        </div>
   </footer>
</body>

</html>