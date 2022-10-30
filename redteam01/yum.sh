cat <<-'EOF' > /etc/yum.repos.d/CentOS-Base.repo
[C6.5-base]
name=CentOS-6.5 - Base
baseurl=http://linuxsoft.cern.ch/centos-vault/6.5/os/$basearch/
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-6
enabled=1
metadata_expire=never

[C6.5-updates]
name=CentOS-6.5 - Updates
baseurl=http://linuxsoft.cern.ch/centos-vault/6.5/updates/$basearch/
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-6
enabled=1
metadata_expire=never

[C6.5-extras]
name=CentOS-6.5 - Extras
baseurl=http://linuxsoft.cern.ch/centos-vault/6.5/extras/$basearch/
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-6
enabled=1
metadata_expire=never

[C6.5-contrib]
name=CentOS-6.5 - Contrib
baseurl=http://linuxsoft.cern.ch/centos-vault/6.5/contrib/$basearch/
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-6
enabled=0
metadata_expire=never

[C6.5-centosplus]
name=CentOS-6.5 - CentOSPlus
baseurl=http://linuxsoft.cern.ch/centos-vault/6.5/centosplus/$basearch/
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-6
enabled=0
metadata_expire=never
EOF