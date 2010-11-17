<div id="headingNav">
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/application_flow">&lt;</a>
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/directory_structure">&gt;</a>
</div>

<style>
code {
	font-size: 14px;
	color: #333;
}
code b,
code i,
code u {
	font-weight: bold;
	font-style: normal;
	text-decoration: none;
}
code b {
	color: #900;
}
code i {
	color: #090;
}
code u {
	color: #009;
}
</style>

<h1>URL routing</h1>

<p>PiePHP URLs normally follow this pattern:<code>http://host/controller/action/parameters</code></p>

<p>So suppose we have the following URL:<code>http://www.piephp.com/<b>forums</b>/<i>post</i>/<u>reply</u>/<u>1234</u></code></p>

<p>The routing logic would take the upper camel case controller and lower camel case action, and append "Controller" and "Action" to them respectively.  So it would try to do something like this:
	<code>
	$controller = new <b>Forums</b>Controller();<br>
	$controller-&gt;<i>post</i>Action(<u>'reply'</u>, <u>'1234'</u>);
	</code>
</p>


<h2>defaultAction</h2>

<p>Suppose we have the following URL:<code>http://www.piephp.com/<b>forums</b></code></p>

<p>The routing logic would take the upper camel case controller and call its defaultAction:
	<code>
	$controller = new <b>Forums</b>Controller();<br>
	$controller-&gt;<i>default</i>Action();
	</code>
</p>


<h2>DefaultController</h2>

<p>Suppose we have the following URL:<code>http://www.piephp.com/</code></p>

<p>The routing logic would just use the DefaultController's defaultAction:
	<code>
	$controller = new <b>Default</b>Controller();<br>
	$controller-&gt;<i>default</i>Action();
	</code>
</p>


<h2>catchAllAction</h2>

<p>Suppose we have the following URL:<code>http://www.piephp.com/<b>news</b>/<i>PiePHP+is+coming+to+Chicago</i></code></p>

<p>The routing logic would look for the NewsController's piePhpIsComingToChicagoAction. When it doesn't find that, it will resort to the NewsController's catchAllAction:
	<code>
	$controller = new <b>News</b>Controller();<br>
	$controller-&gt;catchAllAction(<i>"PiePHP is coming to Chicago"</i>);
	</code>
</p>
