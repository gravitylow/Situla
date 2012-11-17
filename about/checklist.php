<?php
// TODO: Someone does this for me.
include('../heart.php');
echoHeader(2, "About");
?>
    <div class="container">
      <div class="pager previous" style="text-align:left;">
          <a href="http://situla.net/about/" class=>&larr; Back to overview</a>
     </div>
     
     <h3>Developer Checklist</h3>
<p>This page is designed to allow developers to easily assess their projects and see what needs to be done in order to ensure that their projects are compliant. Black items are required, muted grey items are encouraged. Red items will cause your plugin to be rejected on BukkitDev if not met. More info on good developer practice can be found <a href = "http://gravitydevelopment.net/blog/?p=6">here</a>.</p>
<h4>Project details</h4>
<label class="checkbox"><input type="checkbox">Uses an open-source license that allows development to be continued, even if development stops</label>
<label class="checkbox"><input type="checkbox">Provides an updated source of documentation that allows easy use without needing to ask for help</label>
<label class="checkbox muted"><input type="checkbox">Project has an easy setup and is designed to be simple to use</label>
<label class="checkbox muted"><input type="checkbox">As much support as possible is provided to users</label>
<br>
<h4>Code</h4>
<label class="checkbox text-error"><input type="checkbox">Plugin does not download files from outside the dev.bukkit.org domain</label>
<label class="checkbox text-error"><input type="checkbox">File downloads are optional and can be disabled</label>
<label class="checkbox text-error"><input type="checkbox">Plugin will not perform differently when developer is online or advertise the fact</label>
<label class="checkbox text-error"><input type="checkbox">Plugin will not compromise server security or allow users to give themselves permissions/op</label>
<label class="checkbox"><input type="checkbox">Plugin will not advertise its presence or developers</label>
<label class="checkbox"><input type="checkbox">Plugin won't alter files outside of its data directory without permission</label>
<label class="checkbox muted"><input type="checkbox">Plugin provides good range of config options for customisation</label>
      <p></p><hr>
<?php
    echoFooter(false);
?>
