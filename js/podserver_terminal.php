var task_idx = 'NONE';
var terminal_shown = false;
function system_exec(){	
	if (!document.getElementById("dialog")) {
		if (document.getElementById("podserver_current_task")){
			terminal_shown=false;
			showPage("http://<?= $_SERVER['HTTP_HOST'] ?>/system/podserver_current-task.php","podserver_current_task");
		
			var current_task_idx = $.trim(document.getElementById("podserver_current_task").innerHTML);		
			if (current_task_idx != task_idx && (current_task_idx != 'NONE'|| current_task_idx == 'ALL_DONE')){
				if (document.getElementById("podserver_terminal")){
					task_idx = current_task_idx;		
					showPage("http://<?= $_SERVER['HTTP_HOST'] ?>/system/podserver_exec.php","podserver_terminal");
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
						window.parent.openTerminal();
						$("#scroller").mCustomScrollbar("scrollTo","bottom");
					}
				}
			}
		}
	}
	else{
		if (!terminal_shown){
			terminal_shown=true;
			window.parent.openTerminal();
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
			$("#scroller").mCustomScrollbar("scrollTo","bottom");
			}
	}
}
showPage("http://<?= $_SERVER['HTTP_HOST'] ?>/system/podserver_exec.php","podserver_terminal");
window.setInterval("system_exec()",1000);

