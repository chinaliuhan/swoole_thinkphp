echo "loading"
pid=`pidof live_master`
echo $pid
kill -USER1 $pid
echo "loading success"