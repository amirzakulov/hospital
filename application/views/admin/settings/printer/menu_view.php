<h4 class=""><?= $title; ?></h4>
<ul class="nav nav-tabs nav-tabs-solid nav-justified my_nav_tabs border-bottom border-info ml-0 mr-0 mb-4">
	<li class="nav-item">
		<a class="nav-link show <?= ($this->uri->segment(3) == "lab_print") ? "active":"" ?>" href="">Лаборатория</a>
	</li>
	<li class="nav-item">
		<a class="nav-link show <?= ($this->uri->segment(2) == "uzi_print") ? "active":"" ?>" href="">УЗИ</a>
	</li>
</ul>
