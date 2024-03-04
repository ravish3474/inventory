<?php
	include('up-session.php');
	if(isset($_SESSION['employee_id'])){
		
		if(isset($_GET['op'])){
			$op=base64_decode($_GET['op']);
		}
?>
<html lang="en">
	<head><?php include('head.php');?></head>
	<body>
		<div id="main_container" class="container-scroller">rq
		
			<?php include('header.php');?>
			
			<div class="container-fluid page-body-wrapper">
				<?php include('sidebar.php');?>
				<div class="main-panel">
					<div class="content-wrapper">
						<?php 
							if(isset($_GET['vp'])){
								$vp=base64_decode($_GET['vp']);
								include($vp.'.php');
							}else{
								include('dashboard.php');
							}
							
						?>
					</div>
					<?php include('foot.php');?>
				</div>				
			</div>
		</div>
		<?php 
			$vpj="";
			if(isset($_GET['vp'])){
				$vpj=base64_decode($_GET['vp']);
				$ex_vpj=explode('_',$vpj);
				if(isset($ex_vpj[1])){
					if($ex_vpj[1]!='sql'){
						include('jquery.php');
					}
				}else{
					include('jquery.php');
				}
			}else{
				include('jquery.php');
			}
		?>
		<style type="text/css">
			#back2Top {
			    overflow: hidden;
			    z-index: 999;
			    display: none;
			    cursor: pointer;
			    position: fixed;
			    bottom: 50px;
			    right: 10px;
			}

			.magnifier_mode div:hover,.magnifier_mode span:hover,.magnifier_mode th:hover,.magnifier_mode td:hover,.magnifier_mode p:hover,.magnifier_mode pre:hover,.magnifier_mode button:hover,.magnifier_mode i:hover,.magnifier_mode a:hover,.magnifier_mode select:hover{
				font-size: 25px !important;
				height: auto;
			}
		</style>

		<!-- <a id="back2Top" class="btn btn-sm btn-inverse-dark" href="javascript:history.go(-1)">< Go Back</a> -->
		<input type="hidden" id="magnifier_mode_now" value="<?php echo $_SESSION["magnifier_mode"]; ?>">
		<script type="text/javascript">

			magnifierModeChange('<?php echo $_SESSION["magnifier_mode"]; ?>');

			console.log("vp=<?php echo $vpj; ?>"+"<?php echo isset($op)?",op=".$op:""; ?>"+"<?php echo isset($re_id)?",re_id=".$re_id:""; ?>");
			$(window).scroll(function() {
			    var height = $(window).scrollTop();
			    if (height > 100) {
			        $('#back2Top').fadeIn();
			    } else {
			        $('#back2Top').fadeOut();
			    }
			});

			function magnifierModeChange(on_off){
				if(on_off=="on"){
					$('#main_container').addClass("magnifier_mode");
					$('#sp_magni_btn').css("color","#00F");

				}else{
					$('#main_container').removeClass("magnifier_mode");
					$('#sp_magni_btn').css("color","#999");
				}
			}

			function setMagnifierMode(on_off){

				$.ajax({  
					type: "POST",  
					dataType: "json",
					url:"ajax/main/set_magnifier_mode.php" ,
					data:{
						"magnifier_mode":on_off
					},
					success: function(resp){  

						if(resp.result=="success"){
							$('#magnifier_mode_now').val(on_off);
							magnifierModeChange(on_off);
						}else{
							alert(resp.msg);
						}
						
					}  
				});
				
			}

			function switchMagnifierMode(){
				var on_off = $('#magnifier_mode_now').val();
				if(on_off=="on"){
					new_on_off = "off";
				}else{
					new_on_off = "on";
				}
				setMagnifierMode(new_on_off);
			}
		</script>
	</body>
</html>
<?php 
	}else{
		echo '<meta http-equiv="refresh" content="0;URL=login.php">';
	}
?>