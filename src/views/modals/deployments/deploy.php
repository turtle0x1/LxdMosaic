<!-- Modal -->
<div class="modal fade" id="modal-deployments-deploy" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Deploy Deployment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class="table table-bordered" id="deployCloudConfigTable">
              <thead>
                  <tr>
                      <th> Cloud Config </th>
                      <th> Number Of Instances </th>
                      <th> Host </th>
                      <th> Images </th>
                      <th> Extra Profiles </th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="deploy">Deploy</button>
      </div>
    </div>
  </div>
</div>
<script>

var deploymentDeployObj = {
    deploymentId: null
}

$("#modal-deployments-deploy").on("shown.bs.modal", function(){
    $("#deployCloudConfigTable > tbody").empty();
    ajaxRequest(globalUrls.deployments.getDeploymentConfigs, deploymentDeployObj, function(data){
        data = $.parseJSON(data);
        let trs = $();
        $.each(data, function(i, item){
            let x = $(`<tr id="${item.revId}">
                <td>${item.name}</td>
                <td><input name="qty" class="form-control"/></td>
                <td><input name="hosts" class="form-control"/></td>
                <td><input name="image" class="form-control"/></td>
                <td><input name="extraProfiles" class="form-control"/></td>
            </tr>`);
            x.find("input[name=extraProfiles]").tokenInput(globalUrls.profiles.search.getCommonProfiles, {
                queryParam: "profile",
                propertyToSearch: "profile",
                theme: "facebook",
                tokenValue: "Profile_ID"
            });

            x.find("input[name=hosts]").tokenInput(globalUrls.hosts.search.search, {
                queryParam: "host",
                propertyToSearch: "host",
                tokenLimit: 1,
                tokenValue: "hostId",
                preventDuplicates: false,
                theme: "facebook"
            });

            x.find("input[name=image]").tokenInput(globalUrls.images.search.searchAllHosts, {
                queryParam: "image",
                tokenLimit: 1,
                propertyToSearch: "description",
                theme: "facebook",
                tokenValue: "details"
            });

            $("#deployCloudConfigTable > tbody").append(x);
        });

    })
});

$("#modal-deployments-deploy").on("click", "#deploy", function(){
    let btn = $(this);
    let x = [];
    $("#deployCloudConfigTable > tbody > tr").each(function(){
        let p =  $(this).find("input[name=extraProfiles]").tokenInput("get");
        let i =  $(this).find("input[name=image]").tokenInput("get");
        let hosts = mapObjToSignleDimension($(this).find("input[name=hosts]").tokenInput("get"), "hostId");

        p = mapObjToSignleDimension(p, "profile");

        x.push({
            revId: $(this).attr("id"),
            qty: $(this).find("input[name=qty]").val(),
            extraProfiles: p,
            image: i[0]["details"],
            hosts: hosts
        });
    });

    btn.html(`<i class="fa fa-spinner fa-spin fa-fw"></i>Deploying...`);

    let p = $.extend({
        instances: x
    }, deploymentDeployObj);

    ajaxRequest(globalUrls.deployments.deploy, p, function(data){
        btn.html(`Deploy`);
        data = makeToastr(data);
        if(data.state == "error"){
            return false;
        }
        $("#modal-deployments-deploy").modal("toggle");
    })
});
</script>
