/* Truong Group - frontend scripts */
(function(){
  // Mobile drawer
  var burger=document.getElementById('burger'),drawer=document.getElementById('drawer'),scrim=document.getElementById('scrim');
  if(burger&&drawer&&scrim){
	function toggle(open){
	  drawer.classList.toggle('open',open);scrim.classList.toggle('open',open);
	  burger.setAttribute('aria-expanded',open?'true':'false');
	  document.body.style.overflow=open?'hidden':'';
	}
	burger.addEventListener('click',function(){toggle(!drawer.classList.contains('open'))});
	scrim.addEventListener('click',function(){toggle(false)});
	drawer.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){toggle(false)})});
  }

  // Tabs
  var tabs=document.querySelectorAll('.tab');
  tabs.forEach(function(tab){
	tab.addEventListener('click',function(){
	  tabs.forEach(function(t){t.setAttribute('aria-selected','false')});
	  document.querySelectorAll('.panel').forEach(function(p){p.classList.remove('active')});
	  tab.setAttribute('aria-selected','true');
	  document.getElementById(tab.dataset.panel).classList.add('active');
	});
  });

  // Reviews carousel
  var track=document.getElementById('track'),nextBtn=document.getElementById('next'),prevBtn=document.getElementById('prev');
  if(track&&nextBtn&&prevBtn){
	function step(){var c=track.querySelector('.review');return c?c.getBoundingClientRect().width+16:300;}
	nextBtn.addEventListener('click',function(){track.scrollBy({left:step(),behavior:'smooth'})});
	prevBtn.addEventListener('click',function(){track.scrollBy({left:-step(),behavior:'smooth'})});
  }

  // Related show more
  var grid=document.getElementById('relatedGrid'),btn=document.getElementById('moreBtn');
  if(grid&&btn){
	btn.addEventListener('click',function(){
	  var exp=grid.classList.toggle('expanded');
	  btn.textContent=exp?'Show fewer':'Show all treatments';
	});
  }

  // Print-this-section buttons (e.g. the recovery supplies checklist)
  document.querySelectorAll('[data-print-target]').forEach(function(btn){
	btn.addEventListener('click',function(){window.print()});
  });

  // Submenu accordion (icon-only toggle): inserts a "V" button on any
  // top-level menu item that has a nested .sub-menu; click toggles it,
  // opening one closes any other, and clicking outside closes it too.
  var submenuItems=[];
  function closeSubmenuItem(item){
	item.li.classList.remove('is-open');
	item.btn.setAttribute('aria-expanded','false');
  }
  function initSubmenuToggles(menuSelector,toggleClass){
	document.querySelectorAll(menuSelector+' > li.menu-item-has-children').forEach(function(li){
	  var sub=li.querySelector(':scope > ul.sub-menu');
	  if(!sub) return;
	  var btn=document.createElement('button');
	  btn.type='button';
	  btn.className=toggleClass;
	  btn.setAttribute('aria-expanded','false');
	  btn.setAttribute('aria-label','Toggle submenu');
	  li.insertBefore(btn,sub);
	  var item={li:li,btn:btn};
	  submenuItems.push(item);
	  btn.addEventListener('click',function(){
		var willOpen=!li.classList.contains('is-open');
		submenuItems.forEach(closeSubmenuItem);
		if(willOpen){
		  li.classList.add('is-open');
		  btn.setAttribute('aria-expanded','true');
		}
	  });
	});
  }
  initSubmenuToggles('.site-footer__menu','site-footer__submenu-toggle');
  initSubmenuToggles('.site-header__menu','site-header__submenu-toggle');
  document.addEventListener('click',function(e){
	submenuItems.forEach(function(item){
	  if(item.li.classList.contains('is-open')&&!item.li.contains(e.target)) closeSubmenuItem(item);
	});
  });

  // Sticky header scrolled state
  var siteHeader=document.querySelector('.site-header');
  if(siteHeader){
	function onScroll(){siteHeader.classList.toggle('is-scrolled',window.scrollY>10);}
	window.addEventListener('scroll',onScroll,{passive:true});
	onScroll();
  }
})();