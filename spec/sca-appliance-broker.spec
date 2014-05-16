# spec file for package sca-appliance-broker
#
# Copyright (C) 2014 SUSE LLC
#
# This file and all modifications and additions to the pristine
# package are under the same license as the package itself.
#
# Source developed at:
#  https://github.com/g23guy/sca-appliance-broker
#
# norootforbuild
# neededforbuild
%define sca_common sca

Name:         sca-appliance-broker
Summary:      Supportconfig Analysis Appliance Broker
URL:          https://github.com/g23guy/sca-appliance-broker
Group:        System/Monitoring
License:      GPL-2.0
Autoreqprov:  on
Version:      1.3
Release:      34
Source:       %{name}-%{version}.tar.gz
BuildRoot:    %{_tmppath}/%{name}-%{version}
Buildarch:    noarch
Requires:     apache2
Requires:     /usr/bin/dos2unix
Requires:     /usr/sbin/mysqld
Requires:     sca-appliance-common

%description
Monitors inbound supportconfig archives and is responsible for
assigning new and retry archives states for appropriate agent analysis. 

Authors:
--------
    Jason Record <jrecord@suse.com>

%prep
%setup -q

%build
gzip -9f man/*8
gzip -9f man/*5

%install
pwd;ls -la
rm -rf $RPM_BUILD_ROOT
install -d $RPM_BUILD_ROOT/etc/%{sca_common}
install -d $RPM_BUILD_ROOT/srv/www/htdocs/%{sca_common}
install -d $RPM_BUILD_ROOT/usr/sbin
install -d $RPM_BUILD_ROOT/usr/share/man/man8
install -d $RPM_BUILD_ROOT/usr/share/man/man5
install -d $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 444 man/COPYING.GPLv2 $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 644 config/*.conf $RPM_BUILD_ROOT/etc/%{sca_common}
install -m 644 config/* $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 644 websca/index.html $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 640 websca/* $RPM_BUILD_ROOT/srv/www/htdocs/%{sca_common}
install -m 544 bin/* $RPM_BUILD_ROOT/usr/sbin
install -m 644 schema/* $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 644 docs/* $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 644 man/*.8.gz $RPM_BUILD_ROOT/usr/share/man/man8
install -m 644 man/*.5.gz $RPM_BUILD_ROOT/usr/share/man/man5

%files
%defattr(-,root,root)
%dir /etc/%{sca_common}
%dir /srv/www/htdocs/%{sca_common}
%dir /usr/share/doc/packages/%{sca_common}
/usr/sbin/*
%config /etc/%{sca_common}/*
%doc /usr/share/man/man8/*
%doc /usr/share/man/man5/*
%attr(-,wwwrun,www) /srv/www/htdocs/%{sca_common}
%attr(-,wwwrun,www) /usr/share/doc/packages/%{sca_common}/index.html
%doc /usr/share/doc/packages/%{sca_common}/*

%post
if [[ -s /srv/www/htdocs/index.html ]]; then
	if grep -i '<html><body><h1>It works!</h1></body></html>' /srv/www/htdocs/index.html &>/dev/null; then
		mv /srv/www/htdocs/index.html /srv/www/htdocs/index.html.sca_orig
		cp -a /usr/share/doc/packages/%{sca_common}/index.html /srv/www/htdocs/
	else
		echo
		echo "WARNING: File already exists: /srv/www/htdocs/index.html"
		echo " Redirector /usr/share/doc/packages/%{sca_common}/index.html will not be installed."
		echo
	fi
else
	cp -a /usr/share/doc/packages/%{sca_common}/index.html /srv/www/htdocs/
fi

%postun
if [[ -s /srv/www/htdocs/index.html.sca_orig ]]; then
	mv /srv/www/htdocs/index.html.sca_orig /srv/www/htdocs/index.html
elif grep -i 'sca/index.php' /srv/www/htdocs/index.html &>/dev/null; then
	mv /srv/www/htdocs/index.html /srv/www/htdocs/index.html.sca_redirector
fi

%changelog

