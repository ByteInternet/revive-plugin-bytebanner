
all: byteBannerTypes.zip

byteBannerTypes.zip:
	cd $(@:.zip=) && zip -D -r ../$@ . -x \*.git\* \*.svn\* \*tests/* \*extensions\* \*.packages/* \*.bak \*.template.xml \*default.properties
