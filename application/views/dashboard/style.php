<style>

	.panel {
		-webkit-animation: AnimationName 30s ease infinite;
		-moz-animation: AnimationName 30s ease infinite;
		animation: AnimationName 30s ease infinite;
	}
	@-webkit-keyframes AnimationName {
	    0%{background-position:0% 50%}
	    50%{background-position:100% 50%}
	    100%{background-position:0% 50%}
	}
	@-moz-keyframes AnimationName {
	    0%{background-position:0% 50%}
	    50%{background-position:100% 50%}
	    100%{background-position:0% 50%}
	}
	@keyframes AnimationName {
	    0%{background-position:0% 50%}
	    50%{background-position:100% 50%}
	    100%{background-position:0% 50%}
	}
	.panel-map .panel-body {
	    padding-top: 0px;
	    padding: 10px;
	    /*min-height: 16.3vh;*/
	    overflow: hidden;
	}
	.product-img {
	    position: absolute;
	    left: 0px;
	    margin-top: 0%;
	    margin-left: 0%;
	    width: 25%;
	}
	.mini-stat-info {
	    padding-top: 2px;
	}
	.mini-stat-info span {
	    display: block;
	    font-size: 19px;
	    font-weight: 600;
	    color: #fff;
	    padding-top: 21px;
	}
	.text-muted {
	    color: #ffffff;
	    font-weight: 300;
	    font-family: Roboto,sans-serif;
	}
	.counter {
	    text-align: right; 
	}
	.progress.progress-sm {
	    height: 5px !important;
	}
	.progress {
	    overflow: hidden;
	    /*margin-bottom: 18px;*/
	    background-color: #f5f5f5;
	    /*-webkit-box-shadow: none !important;*/
	    box-shadow: none !important;
	    /*height: 10px;*/
	}

	.grid-container {
	  display: flow-root;
	}

	.grid-item {
	  /*text-align: center;*/
	  /*padding: 20px;*/
	  /*font-size: 30px;*/
	}

	.item1 {
	  grid-column: 1 / span 1;
	  grid-row: 1 / span 3;
	}

  	.item2 {
      grid-column: 2 / span 1;
      grid-row: 2 / span 3;
  	}
  	.btn-collapse-onboarding {
	    font-size: 12px;
	    color: #fff;
	    font-weight: 500;
	    padding: 3px 40px 3px 40px;
	    border-radius: 0px 0px 5px 5px;
	    background-image: linear-gradient(147deg, #fe8a39 0%, #fd3838 74%);
	    box-shadow: 0px 2px 3px 1px rgba(136,136,136,0.49);
	    z-index: 3;
	    position: relative;
	    transform: translate(1%, -35%);
    	width: 98%;
	}
	.nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
	    color: #fff;
	    background-color: #f8b125;
	}
	.nav-pills-rounded>li>a {
	    padding-right: 20px;
	    padding-left: 20px;
	    margin-right: 5px;
	    margin-left: 5px;
	    border-radius: 0px; 
	}
	.not-active {
	  pointer-events: none;
	  cursor: default;
	  text-decoration: none;
	  color: black;
	}
	.bg-abu{
		background: #f1f1f1;
	}
	.mb-15 {
		margin-bottom: 15px;
	}
	.li-active {
	    background: #f8b125 !important;
	}
	.li-active a{
		color: #fff;
	}
	.nav-pills>li>a:focus, .nav-pills>li>a:hover {
	    text-decoration: none;
	    background-color: #f8b125;
	}
	@media screen and (min-width: 1450px) {
		.product-img {
			width: 16% !important;
		}
	}
	@media screen and (max-width: 992px) {
		.btn-collapse-onboarding {
		    font-size: 12px;
		    color: #fff;
		    font-weight: 500;
		    padding: 3px 40px 3px 40px;
		    border-radius: 0px 0px 5px 5px;
		    background-image: linear-gradient(147deg, #fe8a39 0%, #fd3838 74%);
		    box-shadow: 0px 2px 3px 1px rgba(136,136,136,0.49);
		    z-index: 3;
		    position: relative;
		    transform: translate(5%, -5%);
		    width: 91%;
		}
		.blog-slider {
		    width: 90%;
		    position: relative;
		    /* max-width: 800px; */
		    margin: 30% auto auto auto;
		    background: #fff;
		    /* box-shadow: 0px 14px 80px rgba(34, 35, 58, 0.2); */
		    padding: 3%;
		    /* border-radius: 25px; */
		    /* height: 400px; */
		    transition: all .3s;
		}
		.w-100{
			width: 100%;
			padding-left: 5%;
			padding-right: 5%;
		}
		.panel {
			margin-left: -2%;
		    margin-right: -2%;
		    /*margin-top: 5%;*/
		}
		.panel-map .panel-body{
			min-height: 14.3vh;
		}
	}
	@media screen and (max-width: 768px) {
		.product-img {
			width: 12% !important;
		}
		.btn-collapse-onboarding {
		    font-size: 12px;
		    color: #fff;
		    font-weight: 500;
		    padding: 3px 40px 3px 40px;
		    border-radius: 0px 0px 5px 5px;
		    background-image: linear-gradient(147deg, #fe8a39 0%, #fd3838 74%);
		    box-shadow: 0px 2px 3px 1px rgba(136,136,136,0.49);
		    z-index: 3;
		    position: relative;
		    transform: translate(5%, -5%);
		    width: 91%;
		}
		.blog-slider {
		    width: 90%;
		    position: relative;
		    /* max-width: 800px; */
		    margin: 30% auto auto auto;
		    background: #fff;
		    /* box-shadow: 0px 14px 80px rgba(34, 35, 58, 0.2); */
		    padding: 3%;
		    /* border-radius: 25px; */
		    /* height: 400px; */
		    transition: all .3s;
		}
		.w-100{
			width: 100%;
			padding-left: 5%;
			padding-right: 5%;
		}
		.panel {
			margin-left: -2%;
		    margin-right: -2%;
		    /*margin-top: 5%;*/
		}
		.panel-map .panel-body{
			min-height: 14.3vh;
		}
	}
	.header-module{
		background: #62a8ea;
		margin-bottom: 20px;
	}
</style>