var task_idx = 'NONE';
var monitor_shown = false;
function system_exec(){	
	if (!document.getElementById("dialog")) {
		if (document.getElementById("podserver_current_task")){
			monitor_shown=false;
			showPage("http://<?= $_SERVER['HTTP_HOST'] ?>/system/system-current-task.php","podserver_current_task");
		
			var current_task_idx = $.trim(document.getElementById("podserver_current_task").innerHTML);		
			if (current_task_idx != task_idx && current_task_idx != 'NONE'){
				if (document.getElementById("podserver_monitor")){
					task_idx = current_task_idx;		
					showPage("http://<?= $_SERVER['HTTP_HOST'] ?>/system/system-exec.php","podserver_monitor");
					$("#scroller").mCustomScrollbar("destroy");
					$("#scroller").mCustomScrollbar({
						scrollButtons:{
							enable:true
						},
						callbacks:{
							onScroll:function(){ 
								$("."+this.attr("id")+"-pos").text(mcs.top);
							}
						}
					});	
					if (current_task_idx != ''){
						$(window.parent.document.getElementById("div_monitor")).show();
						$("#scroller").mCustomScrollbar("scrollTo","bottom");
					}
				}
			}
		}
	}
	else{
		if (!monitor_shown){
			monitor_shown=true;
			$(window.parent.document.getElementById("div_monitor")).show();
			$("#scroller").mCustomScrollbar("destroy");
			$("#scroller").mCustomScrollbar({
				scrollButtons:{
					enable:true
				},
				callbacks:{
					onScroll:function(){ 
						$("."+this.attr("id")+"-pos").text(mcs.top);
					}
				}
			});	
			}
	}
}
showPage("http://<?= $_SERVER['HTTP_HOST'] ?>/system/system-exec.php","podserver_monitor");
window.setInterval("system_exec()",2000);

