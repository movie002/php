del style_dhblog.css style_dh.css style_v.css style_www.css 

copy base.css+layout.css+title.css+layout_tab.css+entry.css+article_index.css+page_navi.css+page_class.css style_v.css  /Y /B
csstidy.exe style_v.css --template=highest --remove_last_;=true style_v.css
copy style_v.css ..\..\v\css\style.css /Y /B

::copy base.css+title.css+layout.css style_s.css  /Y /B
::csstidy.exe style_s.css --template=highest --remove_last_;=true style_s.css
::copy style_s.css ..\..\s\css\style.css /Y /B

copy base.css+layout.css+title.css+entry_dhblog.css+article_index.css+page_navi.css+page_class.css+postbox.css style_www.css  /Y /B
csstidy.exe style_www.css --template=highest --remove_last_;=true style_www.css
copy style_www.css ..\..\www\css\style.css /Y /B

copy base.css+layout.css+title.css+layout_tab.css style_dh.css  /Y /B
csstidy.exe style_dh.css --template=highest --remove_last_;=true style_dh.css
copy style_dh.css ..\..\dh\css\style.css /Y /B

copy base.css+title.css+article_index.css+page_navi.css+page_class.css+layout_dhblog.css+postbox.css+entry_dhblog.css style_dhblog.css  /Y /B
csstidy.exe style_dhblog.css --template=highest --remove_last_;=true style_dhblog.css
copy style_dhblog.css ..\..\dhblog\css\style.css /Y /B