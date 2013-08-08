<?php
/**
 *@fileoverview: [群博客] 数据库配置
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Tue Nov 30 01:48:23 GMT 2010
 *@copyright: sina
 */


return array(
    'wrc_source' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'wrc_source'
    ),
    'wrc_article' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            )
        ),
        'tb_n' => 2,
        'tb_prefix' => 'wrc_article'
    ),
    'wrc_articleContent' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            )
        ),
        'tb_n' => 2,
        'tb_prefix' => 'wrc_article_content'
    ),
    'wrc_jobContent' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'wrc_jobContent'
    ),
    'wrc_tag2jobContent' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'wrc_tag2jobContent'
    ),
    'wrc_tagGroup2jobContent' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'wrc_tagGroup2jobContent'
    ),
    'wrc_job2jobContent' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'wreader',
                'pw' => 'cucued',
                'name' => 'wreader_dev',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'wrc_job2jobContent'
    ),
);
