<div class="span3">
	<div class="row">
		<div class="span3" style="height:70px">
			<h1 class="affix span3" style="text-align:center; margin-left: 0px">myeye</h1>
		</div>
		<div class="span3">
			<ul class="nav nav-list affix span2">
				<li class="nav-header"><?php echo anchor('home', 'Home', array('title' => 'Home'))?></li>
				<li class="nav-header"><?php echo anchor('categories', 'Categories', array('title' => 'Video categories'))?></li>
				<li style="display: <?php echo $display['category']?>"><?php echo anchor('category/movie', 'Movies', array('title' => 'Movies'))?></li>
				<li style="display: <?php echo $display['category']?>"><?php echo anchor('category/tutorial', 'Tutorial', array('title' => 'Tutorial'))?></li>
				<li class="nav-header"><?php echo anchor('channels', 'Channels', array('title' => 'Video channels'))?></li>
				<li style="display: <?php echo $display['channel']?>"><?php echo anchor('channel/box-office', 'Box Office', array('title' => 'Box Office'))?></li>
				<li style="display: <?php echo $display['channel']?>"><?php echo anchor('channel/tutorial', 'Tutorial', array('title' => 'Tutorial'))?></li>
				<li style="display: <?php echo $display['channel']?>"><?php echo anchor('channel/tv-series', 'TV Series', array('title' => 'TV Series'))?></li>
				<li class="nav-header"><?php echo anchor('signin', 'Signin', array('title' => 'Signin'))?></li>
				<li class="nav-header"><?php echo anchor('signup', 'Signup', array('title' => 'Signup'))?></li>
			</ul>
		</div>
	</div>
</div>


