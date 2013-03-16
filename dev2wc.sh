# zanardi 2012-08-13
#
# This script syncs from local development site /var/www/containers to local SVN working copy.
# This is needed because SVN working copy reflects install package structure, which is different from installed component structure

COMPONENT=pizzabox
SITE_ROOT=/var/www/pizzabox
# This gets the folder in which the script is (+1 for automation)
SVN_ROOT="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

rsync -a --exclude="*.svn" $SITE_ROOT/administrator/components/com_$COMPONENT/ $SVN_ROOT/admin/
rsync -a --exclude="*.svn" $SITE_ROOT/components/com_$COMPONENT/ $SVN_ROOT/site/
cp $SITE_ROOT/administrator/language/it-IT/it-IT.com_$COMPONENT.ini $SVN_ROOT/admin/translations
cp $SITE_ROOT/administrator/language/en-GB/en-GB.com_$COMPONENT.ini $SVN_ROOT/admin/translations
cp $SITE_ROOT/language/it-IT/it-IT.com_$COMPONENT.ini $SVN_ROOT/site/language
cp $SITE_ROOT/language/en-GB/en-GB.com_$COMPONENT.ini $SVN_ROOT/site/language
mv $SVN_ROOT/admin/$COMPONENT.xml $SVN_ROOT/
