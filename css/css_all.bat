del style_dhblog.css style_dh.css style_db.css style_www.css 

copy base.css+layout.css+title.css+layout_tab.css+entry.css+article_index.css+page_navi.css+page_class.css style_db.css  /Y /B
csstidy.exe style_db.css --template=highest --remove_last_;=true style_db.css
copy style_db.css ..\..\db\css\style.css /Y /B

copy base.css+layout.css+title.css+entry_dhblog.css+article_index.css+page_navi.css+page_class.css+postbox.css style_www.css  /Y /B
csstidy.exe style_www.css --template=highest --remove_last_;=true style_www.css
copy style_www.css ..\..\www\css\style.css /Y /B

copy base.css+layout.css+title.css+layout_tab.css style_dh.css  /Y /B
csstidy.exe style_dh.css --template=highest --remove_last_;=true style_dh.css
copy style_dh.css ..\..\dh\css\style.css /Y /B

copy base.css+title.css+article_index.css+page_navi.css+page_class.css+layout_dhblog.css+postbox.css+entry_dhblog.css style_dhblog.css  /Y /B
csstidy.exe style_dhblog.css --template=highest --remove_last_;=true style_dhblog.css
copy style_dhblog.css ..\..\dhblog\css\style.css /Y /B