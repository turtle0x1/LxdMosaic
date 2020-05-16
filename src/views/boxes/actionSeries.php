<script src="https://d3js.org/d3.v5.js"></script>
<style>
   .node circle {
     fill: grey;
     stroke: steelblue;
     stroke-width: 3px;
   }

   .node rect {
     fill: #fff;
     stroke: steelblue;
     stroke-width: 3px;
   }

   .node text {
     font: 12px sans-serif;
   }

   .link path {
     fill: none;
     stroke: #ccc;
     stroke-width: 2px;
   }

   .link-danger path {
       stroke: red;
   }

   .link-success path {
       stroke: green;
   }

   .link-skipped path {
       stroke: black;
   }

   .link text {
     font: 12px sans-serif;
   }

   .arrow {
     fill: none;
     stroke: #ccc;
     stroke-width: 1px;
   }
 </style>
<div id="actionSeriesBox" class="boxSlide">
    <div id="actionSeriesView">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                <h4 class="pt-1" id="actionSeriesName"></h4>
                <div class="btn-toolbar float-right">
                  <div class="btn-group mr-2">
                      <button data-toggle="tooltip" data-placement="bottom" title="Delete" class="btn btn-sm btn-danger deleteContainer">
                          <i class="fas fa-trash"></i>
                      </button>
                  </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 border">
            <h4>Execution Plan</h4>
            <div id="actionSeriesTree">
            </div>
        </div>
        <div class="col-md-12 mt-2">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h4>Execution Runs</h4>
                </div>
                <div class="card-body">
                    <table class="table table-dark" id="actionSeriesRunsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Started</th>
                                <th>Finished</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function loadActionSeriesView(){
    changeActiveNav(".viewImages");
    setBreadcrumb(["Action Series"], ["active"], false)
    $(".boxSlide").hide();
    $("#actionSeriesBox").show();
    ajaxRequest(globalUrls.actionSeries.getOverview, {}, (data)=>{
        data = makeToastr(data);
        let lis = `<li class="nav-item viewActionSeries">
            <a class="nav-link text-info" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>
        <li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Existing Series</u></li>`;
        $.each(data, (_, series)=>{
            lis += `<li id="${series.id}" class="nav-item viewSeries">
                <a class="nav-link" href="#">
                <i class="fas fa-robot"></i>
                ${series.name}
                </a>
            </li>`
        });
        $("#sidebar-ul").empty().append(lis);
    });
}

$("#sidebar-ul").on("click", ".viewSeries", function(){
    let actionSeries = $(this).attr("id");
    ajaxRequest(globalUrls.actionSeries.getSeriesOverview, {actionSeries: actionSeries}, (data)=>{
        data = makeToastr(data);
        addBreadcrumbs(["Action Series", data.details.name], ["", "active"], false);
        $("#actionSeriesName").text(data.details.name);
        makeActionSeriesGraph(data.commandTree[0]);
        let trs = "";
        $.each(data.runs, (_, run)=>{
            let fin = run.finished == null ? "Still Running" : moment(run.finished).format("llll");
            trs += `<tr>
                <td><a href="#">${run.id}</a></td>
                <td>${moment(run.started).format("llll")}</td>
                <td>${fin}</td>
            </tr>`
        });
        $("#actionSeriesRunsTable > tbody").empty().append(trs);
    });
});
  // This is how the pro's do it right ?
  // Taken straight from https://stackoverflow.com/a/41603646/4008082
  function makeActionSeriesGraph(treeData, notRunning = true) {
      $("#actionSeriesTree").empty();
        // Set the dimensions and margins of the diagram
         var margin = {
             top: 20,
             right: 90,
             bottom: 30,
             left: 90
           },
           width = 5000 - margin.left - margin.right,
           height = 500 - margin.top - margin.bottom;

         // append the svg object to the body of the page
         // appends a 'group' element to 'svg'
         // moves the 'group' element to the top left margin
         var svg = d3.select("#actionSeriesTree").append("svg")
           .attr("width", $("#actionSeriesTree").width())
           .attr("height", height + margin.top + margin.bottom)
           .append("g")
           .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

         var i = 0,
           duration = 750,
           root;

         // declares a tree layout and assigns the size
         var treemap = d3.tree().size([height, width]);

         // Assigns parent, children, height, depth
         root = d3.hierarchy(treeData, function(d) {
           return d.children;
         });
         root.x0 = height / 2;
         root.y0 = 0;

         update(root);

         // Collapse the node and all it's children
         function collapse(d) {
           if (d.children) {
             d._children = d.children
             d._children.forEach(collapse)
             d.children = null
           }
         }

         function update(source) {

           // Assigns the x and y position for the nodes
           var treeData = treemap(root);

           // Compute the new tree layout.
           var nodes = treeData.descendants(),
             links = treeData.descendants().slice(1);

           // Normalize for fixed-depth.
           nodes.forEach(function(d) {
             d.y = d.depth * 180
           });

           // ****************** Nodes section ***************************

           // Update the nodes...
           var node = svg.selectAll('g.node')
             .data(nodes, function(d) {
               return d.id || (d.id = ++i);
             });

           // Enter any new modes at the parent's previous position.
           var nodeEnter = node.enter().append('g')
             .attr('class', 'node')
             .attr("transform", function(d) {
               return "translate(" + source.y0 + "," + source.x0 + ")";
             })
             .on('click', click);

           // Add Circle for the nodes
           nodeEnter.filter(function(d) {
               return (!d.data.type || d.data.type !== 'data');
             }).append('circle')
             .attr('class', 'node')
             .attr('r', 1e-6)
             .style("fill", function(d) {
                 if(!d.data.hasOwnProperty("result") || d.data.result.return == null){
                     if(d.parent !== null && d.parent.data.hasOwnProperty("result")){
                        return d.parent.data.result.return != d.data.parentReturnAction ? "grey" : "#fff";
                     }
                     return "#fff";
                 }
                 return d.data.result.return == 0 ? "green" : "red";
             });

           nodeEnter.filter(function(d) {
               return (d.data.type && d.data.type === 'data');
             }).append('rect')
             .attr('class', 'node')
             .attr('width', 20)
             .attr('height', 20)
             .attr('y', -10)
             .attr('x', -10)
             .style("fill", function(d) {
                 if(!d.data.hasOwnProperty("result") || d.data.result.return == null){
                     if(d.parent !== null && d.parent.data.hasOwnProperty("result")){
                        return d.parent.data.result.return != d.data.parentReturnAction ? "grey" : "#fff";
                     }
                     return "#fff";
                 }
                 return d.data.result.return == 0 ? "green" : "red";
             });

           // Add labels for the nodes
           nodeEnter.append('text')
             .attr("dy", "2em")
             .attr("x", function(d) {
               return d.children || d._children ? 13 : 13;
             })
             .attr("text-anchor", function(d) {
               return d.children || d._children ? "start" : "start";
             })
             .text(function(d) {
               return d.data.name;
             });

           // UPDATE
           var nodeUpdate = nodeEnter.merge(node);

           // Transition to the proper position for the node
           nodeUpdate.transition()
             .duration(duration)
             .attr("transform", function(d) {
               return "translate(" + d.y + "," + d.x + ")";
             });

           // Update the node attributes and style
           nodeUpdate.select('circle.node')
             .attr('r', 10)
             .style("fill", function(d) {
                 if(!d.data.hasOwnProperty("result") || d.data.result.return == null){
                     if(d.parent !== null && d.parent.data.hasOwnProperty("result")){
                        return d.parent.data.result.return != d.data.parentReturnAction ? "grey" : "#fff";
                     }
                     return "#fff";
                 }
                 return d.data.result.return == 0 ? "green" : "red";
             })
             .attr('cursor', 'pointer');


           // Remove any exiting nodes
           var nodeExit = node.exit().transition()
             .duration(duration)
             .attr("transform", function(d) {
               return "translate(" + source.y + "," + source.x + ")";
             })
             .remove();

           // On exit reduce the node circles size to 0
           nodeExit.select('circle')
             .attr('r', 1e-6);

           // On exit reduce the opacity of text labels
           nodeExit.select('text')
             .style('fill-opacity', 1e-6);

           // ****************** links section ***************************

           // Update the links...
           var link = svg.selectAll('g.link')
             .data(links, function(d) {
               return d.id;
             });

           // Enter any new links at the parent's previous position.
           var linkEnter = link.enter().insert('g', 'g')
             .attr("class", function(d){

                 if(notRunning){
                     let colorClass = d.data.parentReturnAction == 0 ? "success" : "danger";
                     return `link link-${colorClass}`;
                 }

                 // If the parent action hasn't run
                 if(!d.parent.data.hasOwnProperty("result") || d.parent.data.result.return == null){
                     return "link";
                 }

                 // If this action wont run because the parent didn't reach
                 // this actions trigger
                 if(d.parent.data.result.return != d.data.parentReturnAction){
                     return "link link-skipped";
                 }

                 let colorClass = d.parent.data.result.return == 0 ? "success" : "danger";
                 return `link link-${colorClass}`;
             });

           linkEnter.append('path')
             .attr('d', function(d) {
               var o = {
                 x: source.x0,
                 y: source.y0
               }
               return diagonal(o, o)
             });

           linkEnter.append('text')
             .text(function(d,i) {
                 if(notRunning){
                     return '';
                 }
                 return d.data.parentReturnAction == 0 ? "On Success" : "On Failure";
             })
             .attr('dy', "-1em");

           // UPDATE
           var linkUpdate = linkEnter.merge(link);

           // Transition back to the parent element position
           linkUpdate.select('path').transition()
             .duration(duration)
             .attr('d', function(d) {
               return diagonal(d, d.parent)
             });

           linkUpdate.select('text').transition()
             .duration(duration)
             .attr('transform', function(d){
               if (d.parent) {
                 return 'translate(' + ((d.parent.y + d.y) / 2) + ',' + ((d.parent.x + d.x) / 2) + ')'
               }
             })

           // Remove any exiting links
           link.exit().each(function(d){
             d.parent.index = 0;
           })

           var linkExit = link.exit()
             .transition()
             .duration(duration);

           linkExit.select('path')
             .attr('d', function(d) {
               var o = {
                 x: source.x,
                 y: source.y
               }
               return diagonal(o, o)
             })

           linkExit.select('text')
             .style('opacity', 0);

           linkExit.remove();

           // Store the old positions for transition.
           nodes.forEach(function(d) {
             d.x0 = d.x;
             d.y0 = d.y;
           });

           // Creates a curved (diagonal) path from parent to the child nodes
           function diagonal(s, d) {

             path = `M ${s.y} ${s.x}
                 C ${(s.y + d.y) / 2} ${s.x},
                   ${(s.y + d.y) / 2} ${d.x},
                   ${d.y} ${d.x}`

             return path
           }

           // Toggle children on click.
           function click(d) {
             if (d.children) {
               d._children = d.children;
               d.children = null;
             } else {
               d.children = d._children;
               d._children = null;
             }
             update(d);
           }
         }
  }

</script>
