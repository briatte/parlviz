<?php

  if(count($_GET) > 0) {
    if(!empty($_GET['t'])) $t = basename($_GET['t']);
    if(!empty($_GET['chamber'])) $ch = basename($_GET['chamber']);
  }

  // default legislature
  if(!isset($t)) $t = '1996';

  // default chamber
  if(!isset($ch)) $ch = 'ca';

  if($ch == 'ca') {
    $chamber = 'Camera Deputaților';
    $members = '<abbr title="Members of Parliament">MPs</abbr>';
    $source  = 'http://www.cdep.ro/';
  }
  else {
    $chamber = 'Senat';
    $members = 'senators';
    $source  = 'http://www.senat.ro/';
  }

  $y = array(
    '1996' => '1996&mdash;2000',
    '2000' => '2000&mdash;2004',
    '2004' => '2004&mdash;2008',
    '2008' => '2008&mdash;2012',
    '2012' => '2012&mdash;');

  $c = $y;

  foreach ($c as $i => $j)
    $c[ $i ] = '';

  $c[ $t ] = 'here';

  // ongoing legislature
  $be = 'was';
  if($t == '2012') $be = 'is';

  $have = 'had';
  if($t == '2012') $have = 'has had';

  // initial box
  $box =
    '<p>This graph shows Romanian ' . str_replace('<abbr title="Members of Parliament">MPs</abbr>', 'Members of Parliament (<abbr title="Members of Parliament">MPs</abbr>)', $members) . ' during years ' . $y[ $t ] . '. ' .
    'A link between two ' . $members . ' indicates that they cosponsored at least one bill together.</p>' .
    '<div id="details"><h3><i class="fa fa-cube"></i> Details</h3>' .
    '<p>The network is based on /bills cosponsored bills. It contains /edges directed edges ' .
    'that connect the first author of each bill to its cosponsor(s). The /nodes nodes are sized proportionally to their ' .
    '<a href="http://toreopsahl.com/tnet/weighted-networks/node-centrality/">weighted degree</a>.</p>' .
    '<p>Group colors&nbsp;&nbsp; /colortext</p></div>';

?>
<!doctype html>
<html>
<head>
  <title>
    Cosponsorship networks in the Romanian Parliament:
    <?php echo $chamber; ?>, years <?php echo $t; ?>
  </title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600" />
  <link rel="stylesheet" type="text/css" href="../assets/styles.css" />
  <link rel="stylesheet" type="text/css" href="../assets/font-awesome-4.1.0/css/font-awesome.min.css">
  <style type="text/css" media="screen">body { background: #100; }</style>
  <script type="text/javascript" src="../assets/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="../assets/jquery.smart_autocomplete.min.js"></script>
  <script type="text/javascript" src="../assets/sigmajs-release-v1.0.2/sigma.min.js"></script>
  <script type="text/javascript" src="../assets/sigmajs-release-v1.0.2/plugins/sigma.parsers.gexf.min.js"></script>
  <script type="text/javascript" src="../assets/sigmajs-release-v1.0.2/plugins/sigma.layout.forceAtlas2.min.js"></script>
</head>
<body>

<div id="sigma-container">

  <div id="controls" class="bg_se">

    <h1>romanian parliament</h1>

    <h2>
      <a href="<?php echo $source; ?>" title="<?php echo $chamber; ?>">
        <img src="logo_ro.png" height="25" alt="logo">
      </a>
      &nbsp;<?php
        echo $chamber . ', ';
        if($ch == 'ca') echo '<br>';
        echo $y[ $t ];
      ?>
    </h2>

    <!-- graph selector -->
    <nav>
      Chamber&nbsp;&nbsp;
      <a href="?chamber=ca&amp;t=<?php echo $t; ?>" class="<?php if($ch == 'ca') echo 'here'; ?>">Lower</a>&nbsp;&nbsp;
      <a href="?chamber=se&amp;t=<?php echo $t; ?>" class="<?php if($ch == 'se') echo 'here'; ?>">Upper</a><br>
      Legislature
        <?php
        foreach ($y as $i => $j)
          echo '&nbsp;&nbsp; <a href="?chamber=' . $ch . '&amp;t=' . $i . '" class="' . $c[ $i ] . '">' . $j . '</a>';
        ?>
    </nav>

    <!-- user search field -->
    <form action="/" method="post" class="search-nodes-form">
      <fieldset id="search-nodes-fieldset">
        <div></div>
      </fieldset>
    </form>

    <!-- buttons and sources -->
    <footer>

      <ul>
        <li>Click a node to show its ego network.</li>
        <li>Double click the graph to zoom in.</li>

        <!-- show/hide -->
        <li>
          Hide&nbsp;
          <label title="Do not draw network ties (vertex edges).">
            <input type="checkbox" id="showEdges" />
            Edges
          </label>
          &nbsp;
          <label title="Do not add labels to nodes (MP names) when zooming in.">
            <input type="checkbox" id="showLabels" />
            Labels
          </label>
          &nbsp;
          <label title="Draw only ties formed among frequent cosponsors (above mean edge weight).">
            <input type="checkbox" id="showSparse" />
            Weak ties
          </label>
        </li>
      </ul>

      <!-- zoom -->
      <p>
        <a href="#" id="recenter-camera" class="button" title="Reset graph to initial zoom position.">Reset zoom</a>&nbsp;&nbsp;
        <a href="#" id="toggle-layout" class="button" title="Animate with Force Atlas 2.">Animate</a>
        <small><a href="https://gephi.org/2011/forceatlas2-the-new-version-of-our-home-brew-layout/" title="Details on the Force Atlas 2 algorithm."><i class="fa fa-info-circle"></i></a></small>
      </p>

      <!-- tweet -->
      <p>
        <a href="http://twitter.com/share?text=Cosponsorship%20networks%20in%20the%20Romanian%20Parliament%20using%20%23rstats%20and%20@sigmajs,%20by%20@phnk:&amp;url=<?php echo 'http://' . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" class="button" title="Share this page on Twitter."><i class="fa fa-twitter"></i>&nbsp;Tweet</a>&nbsp;&nbsp;
        <a href="https://github.com/briatte/parlviz" class="button" title="Get the visualization code from GitHub."><i class="fa fa-github"></i>&nbsp;Code</a>
      </p>

      <!-- creds -->
      <ul>
        <li>
          Data from <a href="http://www.cdep.ro/">cdep.ro</a> (autumn 2014)
        </li>

        <li>
          Download&nbsp;&nbsp;
          <i class="fa fa-file-o"></i>&nbsp;&nbsp;
          <a href="net_ro_<?php echo $ch . $t; ?>.gexf" title="Download this graph (GEXF, readable with Gephi)">network</a>&nbsp;&nbsp;
          <i class="fa fa-files-o"></i>&nbsp;&nbsp;
          <a href="net_ro_<?php echo $ch; ?>.zip" title="Download all <?php echo $chamber; ?> graphs (GEXF, readable with Gephi)">full series</a>&nbsp;&nbsp;
          <i class="fa fa-file-image-o"></i>&nbsp;&nbsp;
          <a href="plots.html">plots</a>
        </li>
      </ul>

      <div id="about">
        <h3><i class="fa fa-eye"></i>&nbsp;More networks</h3>
        <ul>
          <li><a href="/nationalrat">Austria</a></li>
          <li><a href="/belparl">Belgium</a></li>
          <li><a href="/bgparl">Bulgaria</a></li>
          <li><a href="/poslanecka">Czech Republic</a></li>
          <li><a href="/folketinget">Denmark</a></li>
          <li><a href="/epam">European Union</a></li>
          <li><a href="/eduskunta">Finland</a></li>
          <li><a href="/parlement">France</a></li>
          <li><a href="/orszaggyules">Hungary</a></li>
          <li><a href="/althing">Iceland</a></li>
          <li><a href="/parlamento">Italy</a></li>
          <li><a href="/seimas">Lithuania</a></li>
          <li><a href="/stortinget">Norway</a></li>
          <!-- <li><a href="/parlamentul">Romania</a></li> -->
          <li><a href="/riksdag">Sweden</a></li>
          <li><a href="/swparl">Switzerland</a></li>
          <li><a href="/marsad">Tunisia</a></li>
        </ul>
      </div>

    </footer>
    <div id="graph-container"></div>
  </div>

  <div id="box" class="bg_se">
    <?php echo $box; ?>
  </div>

</div>

<script>
// read network dimensions and meta attributes
//
fillBox = function(x, y, z) {

  document.getElementById('box').innerHTML = document.getElementById('box').innerHTML.replace('/nodes', x.length).replace('/edges', y.length).replace('/colortext', z);

  $.ajax({
    type: "GET",
    url: document.title.replace('Cosponsorship networks in the Romanian Parliament: ', 'net_ro_').replace('Camera Deputaților', 'ca').replace('Senat', 'se').replace(', years ', '') + '.gexf',
    dataType: "xml",
    success: function(xml) {
      var b = $(xml).find('description').text().replace('legislative cosponsorship network, fruchtermanreingold placement, ', '').replace(' bills', '');
      document.getElementById('box').innerHTML = document.getElementById('box').innerHTML.replace('/bills', b);
    }
  });

};

// Add a method to the graph model that returns an
// object with every neighbors of a node inside:
sigma.classes.graph.addMethod('neighbors', function(nodeId) {
  var k,
      neighbors = {},
      index = this.allNeighborsIndex[nodeId] || {};

  for (k in index)
    neighbors[k] = this.nodesIndex[k];

  return neighbors;
});

sigma.classes.graph.addMethod('getNeighborsCount', function(nodeId) {
  return this.allNeighborsCount[nodeId];
});

sigma.parsers.gexf(
  document.title.replace('Cosponsorship networks in the Romanian Parliament: ', 'net_ro_').replace('Camera Deputaților', 'ca').replace('Senat', 'se').replace(', years ', '') + '.gexf',
  { // Here is the ID of the DOM element that
    // will contain the graph:
    container: 'sigma-container'
  },
  function(s) {

    // initial edges
    s.graph.edges().forEach(function(e) {
      e.originalColor = e.color;
      e.type = 'arrow';
    });

    // box
    var parties = [ 'Partidul Poporului - Dan Diaconescu', 'Partidul Verde',
      'Partidul Ecologist Român', 'Federația Ecologistă din România', 'Partidul Social Democrat',
      'Uniunea Naţională pentru Progresul României', 'Frontul Salvării Naționale',
      'Partidul Democrat-Liberal', 'Partidul Naţional Ţaranesc Creştin-Democrat',
      'Partidul Democrat Agrar din România', 'Partidul Conservator',
      'Partidul Unității Națiunii Române', 'Uniunea Democrată Maghiară din România',
      'Partidul Național Liberal', 'Partidul România Mare',
      'Ethnic/Linguistic minorities', 'Independent' ];
    var colors = new Array(parties.length);

    // initial nodes
    s.graph.nodes().forEach(function(n) {
      // Minorities
      if(n.attributes['party'] == 'Minoritatilor')
        n.attributes['party'] = 'Ethnic/Linguistic minorities';
      // find party colors
      if(parties.indexOf(n.attributes['party']) != -1)
        colors[ jQuery.inArray(n.attributes['party'], parties) ] = n.color;
      n.originalSize = n.size;
      n.originalColor = n.color;
      n.originalX = n.x;
      n.originalY = n.y;
    });

    // box text
    var t = '';
    for (i = 0; i < parties.length; i++) {
      if(typeof colors[i] != 'undefined')
        t = t + '&nbsp;<span style="color:' +
          colors[i].replace('0.5)', '1)') +
          '">' + parties[i].replace(new RegExp(' ', 'g'), '&nbsp;') + '</span> ';
    };

    // pass network dimensions
    fillBox(s.graph.nodes(), s.graph.edges(), t);

    // When a node is clicked, we check for each node
    // if it is a neighbor of the clicked one. If not,
    // we set its color as grey, and else, it takes its
    // original color.
    // We do the same for the edges, and we only keep
    // edges that have both extremities colored.
    s.bind('clickNode', function(e) {
      var nodeId = e.data.node.id,
          toKeep = s.graph.neighbors(nodeId);
      toKeep[nodeId] = e.data.node;

      s.graph.nodes().forEach(function(n) {
        if (toKeep[n.id])
          n.color = n.originalColor;
        else
          n.color = '#555';
      });

      s.graph.edges().forEach(function(e) {
        if (toKeep[e.source] && toKeep[e.target])
          e.color = e.originalColor;
        else
          e.color = '#333';
      });

      var profile = '<a href="http://www.cdep.ro/pls/' + e.data.node.attributes['url'] +
        '" title="Go to profile (<?php echo $chamber; ?>, new window)" target="_blank">';

      // transparency
      var rgba = e.data.node.color.replace('0.5)', '0.25)');

      // photo
      var photo = '';
      if(typeof e.data.node.attributes['photo'] != 'undefined')
        photo = profile + '<img height="128px" src="photos/' + e.data.node.attributes['photo'] + '" alt="photo" /></a> ';

      // name and party
      var id = profile + e.data.node.label + '</a> <span title="Political party affiliation(s)" style="color:' + rgba.replace('0.25)', '1)') + ';">(' + e.data.node.attributes['party'] + ')</span>';

      console.log(e.data.node.attributes['party']);
      if(e.data.node.attributes['party'] == 'Ethnic/Linguistic minorities')
        id = id.replace('(Ethnic/Linguistic minorities)', '').replace('</a> <', '</a><');
      
      // activity stats
      var stat = ' who <?php echo $have; ?> <span title="unweighted Freeman degree">' +
        s.graph.getNeighborsCount(nodeId) + ' cosponsor(s)</span> on ' +
        e.data.node.attributes['n_bills'] + ' bill(s) during the legislature.</p>';

      // constituency
      var constituency = ' representing <a title="Go to Wikipedia English entry (new window)" target="_blank" href="https://en.wikipedia.org/wiki/' +
        e.data.node.attributes['constituency'] + '">' + e.data.node.attributes['constituency'].replace(new RegExp('_', 'g'), ' ') + '</a>';
        
      if(e.data.node.attributes['constituency'] == "Romanian_diaspora")
        constituency = constituency.replace('representing', 'representing the');

      if(document.title.match('Camera'))
        document.getElementById('box').innerHTML = '<p style="min-height:150px; background:' + rgba + ';">' +
        photo + 'You selected ' + id + ', an <abbr title="Member of Parliament">MP</abbr>' + constituency + stat;
      else
        document.getElementById('box').innerHTML = '<p style="background:' + rgba + ';">' +
        photo + 'You selected ' + id + ', a senator' + constituency + stat;

      // Since the data has been modified, we need to
      // call the refresh method to make the colors
      // update effective.
      s.refresh();
    });

    // When the stage is clicked, we just color each
    // node and edge with its original color.
    s.bind('clickStage', function(e) {

      s.graph.nodes().forEach(function(n) {
        n.color = n.originalColor;
        n.size = n.originalSize;
      });

      s.graph.edges().forEach(function(e) {
        e.color = e.originalColor;
      });

      // Same as in the previous event:
      s.refresh();

      // reinitialize box
      document.getElementById('box').innerHTML = '<?php echo $box; ?>';

      // pass network dimensions (again)
      fillBox(s.graph.nodes(), s.graph.edges(), t);

    });

    s.settings({
      defaultEdgeColor: '#333',
      edgeColor: 'source',
      minNodeSize: 2,
      maxNodeSize: 6,
      defaultLabelColor: '#fff',
      defaultLabelSize: 18,
      font: "source sans pro",
      minEdgeSize: .3,
      maxEdgeSize: .9,
      labelHoverBGColor: 'node',
      defaultLabelHoverColor: '#fff',
      labelHoverShadow: 'node'
    });

    // autocomplete search field
    //
    $('#search-nodes-fieldset > div').remove();
    $('<div>' +
        '<label for="search-nodes">' +
          'Search' +
        '</label>' +
        '<input type="text" autocomplete="off" id="search-nodes"/>' +
      '</div>').appendTo('#search-nodes-fieldset');

    $('#search-nodes-fieldset #search-nodes').smartAutoComplete({
      source: s.graph.nodes().map(function(n){
        return n.label;
      })
    }).bind('itemSelect', function(e) {
      var label = e.smartAutocompleteData.item.innerText;

      // find node and neighbours
      var id = 0,
          nodeId = 0,
          toKeep = new Array();

      s.graph.nodes().forEach(function(n) {
        if (n.label == label) {
          n.size = 100;
          id = n.id;
          nodeId = n.id,
          toKeep = s.graph.neighbors(nodeId);
        } else {
          n.size = 10;
        }
      });

      // color selected nodes
      s.graph.nodes().forEach(function(n) {
        if (n.id == id)
          n.color = n.originalColor;
        else if(toKeep[n.id])
          n.color = '#ccc';
        else
          n.color = '#333';
      });

      // tone down edges
      s.graph.edges().forEach(function(e) {
        e.color = '#333';
      });

      s.refresh();

    });

    // protect search field
    //
    $('form.search-nodes-form').submit(function(e) {
      e.preventDefault();
    });

    // show it all, finally
    s.refresh();

    // hide edges
    //
    document.getElementById('showEdges').addEventListener('change',
    function(e){
      if (e.target.checked) {
        s.settings({
          drawEdges: false
        });
      } else {
        s.settings({
          drawEdges: true
        });
      }
      s.refresh();
    });

    // hide labels
    //
    document.getElementById('showLabels').addEventListener('change',
    function(e){
      if (e.target.checked) {
        s.settings({
          drawLabels: false
        });
      } else {
        s.settings({
          drawLabels: true
        });
      }
      s.refresh();
    });

    // hide sparse ties
    //
    document.getElementById('showSparse').addEventListener('change',
    function(e){
      var sum = 0;
      s.graph.edges().forEach(function(e) {
        sum = sum + e.weight;
      });
      sum = sum / s.graph.edges().length;
      if (e.target.checked) {
        s.graph.edges().forEach(function(e) {
          // use upper quartile marker
          if(e.weight < sum)
            e.color = 'rgba(66,66,66,0)';
        });
        s.settings({
          minEdgeSize: 0,
          maxEdgeSize: 2.7
        });
      } else {
        s.graph.edges().forEach(function(e) {
          e.color = e.originalColor;
        });
        s.settings({
          minEdgeSize: .3,
          maxEdgeSize: .9,
        });
      }
      s.refresh();
    });

    // force atlas
    //
    document.getElementById('toggle-layout').addEventListener('click',
    function() {
      if ((s.forceatlas2 || {}).isRunning) {
        s.stopForceAtlas2();
        document.getElementById('toggle-layout').innerHTML = 'Animate';
      } else {
        s.startForceAtlas2();
        document.getElementById('toggle-layout').innerHTML = 'Stop';
      }
    });

    // reset zoom
    //
    document.getElementById('recenter-camera').addEventListener('click',
    function() {
      s.cameras[0].goTo({
                x: 0,
                y: 0,
                angle: 0,
                ratio: 1
              });
    });

  }
);
</script>

</body>
</html>
