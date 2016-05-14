# -*- coding: utf-8 -*-
import sys, urllib, urllib2, json

url = "http://apis.baidu.com/apistore/pullword/words?source="
mode = "&param1=0.8&param2=0"
input1 = "科比公司申请注册“黑曼巴”将用于运动装备"
#url = 'http://120.27.240.52/get.php?source=%E6%B8%85%E5%8D%8E%E5%A4%A7%E5%AD%A6%E6%98%AF%E5%A5%BD%E5%AD%A6%E6%A0%A1&param1=0&param2=1'

def split_word(input):
    req = urllib2.Request(url + input + mode)
    req.add_header("apikey", "e9efbd5ac9db0c055b973011482a4418")
    resp = urllib2.urlopen(req)
    content = resp.read()
    return content
    #if(content):
    #    print content
    #return content.split('[\r\n]')
    #return dict(conten = content)

print split_word(input1)