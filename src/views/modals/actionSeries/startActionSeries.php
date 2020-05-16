<!-- Modal -->
<div class="modal fade" id="modal-actionSeries-start" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Start Action Series</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="startActionSeriesModalBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="start">Start</button>
      </div>
    </div>
  </div>
</div>
<script>

var startActionSeries = {
    actionSeries: null,
    callback: null
}
// make a longer function name i dare u
function makeHostInstanceHtmlForActionSeriesStart(member){
    let x = `<h5> Host: ${member.alias} </h5>`;
    $.each(member.instances, (_, instance)=>{
        x += `<h4 class='mt-3 ml-2 cursor-pointer d-inline'><span data-instance="${instance}" data-host-id="${member.hostId}" class='badge badge-secondary selectInstance'>${instance}</span></h4>`
    });
     x += '<hr/>';
    return x;
}

$("#modal-actionSeries-start").on("click", "#start", function(){
    let x = {};
    $("#modal-actionSeries-start").find(".badge-primary").each(function(){
        let d = $(this).data();
        let hId = d.hostId;
        if(!x.hasOwnProperty(hId)){
            x[hId] = [];
        }
        x[hId].push(d.instance);
    });
    let p = {instancesByHost: x, actionSeries: startActionSeries.actionSeries};
    ajaxRequest(globalUrls.actionSeries.startRun, p, (data)=>{
        console.log(data);
    });
});

$("#modal-actionSeries-start").on("click", ".selectInstance", function(){
    if($(this).hasClass("badge-primary")){
        $(this).removeClass("badge-primary");
        $(this).addClass("badge-secondary");
    }else{
        $(this).addClass("badge-primary");
        $(this).removeClass("badge-secondary");
    }
});

$("#modal-actionSeries-start").on("shown.bs.modal", function(){

    ajaxRequest(globalUrls.hosts.getInstances, {}, (data)=>{
        data = makeToastr(data);
        let html = "";
        $.each(data.clusters, (clusterIndex, clusterMembers)=>{
            html += `<h4> Cluster ${clusterIndex} </h4>`;
            $.each(clusterMembers, (_, member)=>{
                html += makeHostInstanceHtmlForActionSeriesStart(member);
            });
        });
        html += `<h4> Stanadlone Hosts </h4>`;
        $.each(data.standalone, (_, member)=>{
            html += makeHostInstanceHtmlForActionSeriesStart(member);
        });

        $("#startActionSeriesModalBody").empty().append(html);
    });
});
</script>
