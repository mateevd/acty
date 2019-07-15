!function(t){var a={};function e(i){if(a[i])return a[i].exports;var s=a[i]={i:i,l:!1,exports:{}};return t[i].call(s.exports,s,s.exports,e),s.l=!0,s.exports}e.m=t,e.c=a,e.d=function(t,a,i){e.o(t,a)||Object.defineProperty(t,a,{configurable:!1,enumerable:!0,get:i})},e.n=function(t){var a=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(a,"a",a),a},e.o=function(t,a){return Object.prototype.hasOwnProperty.call(t,a)},e.p="/",e(e.s=34)}({34:function(t,a,e){t.exports=e(35)},35:function(t,a){$(function(){var t=localStorage.getItem("tasks-tab-active");t?$('#tasks-tab-selection a[href="'+t+'"]').tab("show"):$('#tasks-tab-selection a[href="#tasks_user"]').tab("show"),$('#tasks-tab-selection a[data-toggle="tab"]').click(function(t){var a=$(t.target).attr("href");a||(a=$(this).attr("href")),localStorage.setItem("tasks-tab-active",a),$('#tasks-tab-selection a[href="'+a+'"]').tab("show")}),$(document).on("click","#createTaskBtn",function(){var t=JSON.parse($(this).data("phase"));$(".createTask #phase_id").val(t.phase_id),$(".createTask #phase_name").text(t.phase_name),$(".createTask #activity_id").val(t.activity_id)}),$(document).on("click","#editTaskButton",function(){var t=JSON.parse($(this).data("task"));$.each(t,function(t,a){$("#editTask #"+t).val(a)});var a=JSON.parse($(this).data("phase_id")),e=JSON.parse($(this).data("activity_id"));$("#editTask #phase_id").val(a),$("#editTask #activity_id").val(e),$("#editTask #task_name").text(t.task_name),$("#editTask #task_id").text("id=".concat(t.task_id))}),$(document).on("click","#terminateTaskButton",function(){var t=JSON.parse($(this).data("task_id")),a=JSON.parse($(this).data("task_name")),e=JSON.parse($(this).data("phase_id")),i=JSON.parse($(this).data("activity_id"));$("#terminateTask #task_id").val(t),$("#terminateTask #task_name").text(a),$("#terminateTask #phase_id").val(e),$("#terminateTask #activity_id").val(i),$("#terminateTask #task_id").text("id=".concat(t))}),$(document).on("click","#activateTaskButton",function(){var t=JSON.parse($(this).data("task_id")),a=JSON.parse($(this).data("task_name")),e=JSON.parse($(this).data("phase_id")),i=JSON.parse($(this).data("activity_id"));$("#activateTask #task_id").val(t),$("#activateTask #task_name").text(a),$("#activateTask #phase_id").val(e),$("#activateTask #activity_id").val(i),$("#activateTask #task_id").text("id=".concat(t))}),$(document).on("click","#deleteTaskButton",function(){var t=JSON.parse($(this).data("task_id")),a=JSON.parse($(this).data("task_name")),e=JSON.parse($(this).data("phase_id")),i=JSON.parse($(this).data("activity_id"));$("#deleteTask #task_id").val(t),$("#deleteTask #task_name").text(a),$("#deleteTask #phase_id").val(e),$("#deleteTask #activity_id").val(i),$("#deleteTask #task_id").text("id=".concat(t))}),$(document).on("click","#deleteMultiTaskButton",function(){var t=JSON.parse($(this).data("activity_id"));$("#deleteMultiTask #activity_id").val(t)}),$(document).on("click","#terminateMultiTaskButton",function(){var t=JSON.parse($(this).data("activity_id"));$("#terminateMultiTask #activity_id").val(t)}),$(document).on("click","#activateMultiTaskButton",function(){var t=JSON.parse($(this).data("activity_id"));$("#activateMultiTask #activity_id").val(t)}),$(document).on("click","#copyTaskButton",function(){var t=JSON.parse($(this).data("task_id")),a=JSON.parse($(this).data("task_name"));$("#copySingleTask #task_id").val(t),$("#copySingleTask #task_name").val(a),$("#copySingleTask #task_id").text("id=".concat(t))}),$(".multi-tasks-select").prop("disabled","disabled"),$(".multi-tasks-select-checkboxes").change(function(){1==$(".multi-tasks-select-checkboxes").is(":checked")?$(".multi-tasks-select").prop("disabled",!1):$(".multi-tasks-select").prop("disabled",!0)}),$(".multi-tasks-select").click(function(){var t=[];$(".multi-tasks-select-checkboxes").each(function(){$(this).is(":checked")&&t.push(this.value)}),$("#deleteMultiTask #task_id").val(t),$("#moveMultiTask #task_id").val(t),$("#copyMultiTask #task_id").val(t),$("#terminateMultiTask #task_id").val(t),$("#activateMultiTask #task_id").val(t)}),$("#task_activities_list").change(function(){var t;$("#task_phases_list").prop("disabled",!1),""===$("#task_activities_list option:selected").text()?($("#task_phases_list").empty(),$("#task_phases_list").prop("disabled","disabled")):(t=$("#task_activities_list option:selected")[0].value,$.getJSON("/tasks/getPhases/"+t,function(t){var a=$("#task_phases_list");a.empty(),$.each(t.phases_list,function(t,e){a.append($("<option></option>").attr("value",t).text(e))}),sortSelect("task_phases_list")}))}),$("#task_activities_list2").change(function(){var t;$("#task_phases_list2").prop("disabled",!1),""===$("#task_activities_list2 option:selected").text()?($("#task_phases_list2").empty(),$("#task_phases_list2").prop("disabled","disabled")):(t=$("#task_activities_list2 option:selected")[0].value,$.getJSON("/tasks/getPhases/"+t,function(t){var a=$("#task_phases_list2");a.empty(),$.each(t.phases_list,function(t,e){a.append($("<option></option>").attr("value",t).text(e))}),sortSelect("task_phases_list2")}))}),$("#task_create").submit(function(t){var a,e=!1,i="";$("#task_create").find("select, textarea, input").each(function(){$(this).prop("hidden")||$(this).prop("required")&&($(this).val()||(e=!0,a=$(this).attr("name"),i+=a+" is required.\n"))}),console.log(i),e?t.preventDefault():$(this).find("#btn-submit-form").addClass("apply-spin")}),$("#task_update").submit(function(t){var a,e=!1,i="";$("#task_update").find("select, textarea, input").each(function(){$(this).prop("hidden")||$(this).prop("required")&&($(this).val()||(e=!0,a=$(this).attr("name"),i+=a+" is required.\n"))}),console.log(i),e?t.preventDefault():$(this).find("#btn-submit-form").addClass("apply-spin")}),$("#task_terminate_multi").submit(function(t){var a,e=!1,i="";$("#task_terminate_multi").find("select, textarea, input").each(function(){$(this).prop("hidden")||$(this).prop("required")&&($(this).val()||(e=!0,a=$(this).attr("name"),i+=a+" is required.\n"))}),console.log(i),e?t.preventDefault():$(this).find("#btn-submit-form").addClass("apply-spin")}),$("#task_move_multi").submit(function(t){var a,e=!1,i="";$("#task_move_multi").find("select, textarea, input").each(function(){$(this).prop("hidden")||$(this).prop("required")&&($(this).val()||(e=!0,a=$(this).attr("name"),i+=a+" is required.\n"))}),console.log(i),e?t.preventDefault():$(this).find("#btn-submit-form").addClass("apply-spin")}),$("#task_copy_multi").submit(function(t){var a,e=!1,i="";$("#task_copy_multi").find("select, textarea, input").each(function(){$(this).prop("hidden")||$(this).prop("required")&&($(this).val()||(e=!0,a=$(this).attr("name"),i+=a+" is required.\n"))}),console.log(i),e?t.preventDefault():$(this).find("#btn-submit-form").addClass("apply-spin")}),$("#task_create_public").submit(function(t){var a,e=!1,i="";$("#task_create_public").find("select, textarea, input").each(function(){$(this).prop("hidden")||$(this).prop("required")&&($(this).val()||(e=!0,a=$(this).attr("name"),i+=a+" is required.\n"))}),console.log(i),e?t.preventDefault():$(this).find("#btn-submit-form").addClass("apply-spin")})})}});