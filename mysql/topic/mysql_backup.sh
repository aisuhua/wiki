#!/bin/bash
# Simple script to backup MySQL databases

# Parent backup directory
backup_parent_dir="/var/backups/mysql"

# MySQL settings
mysql_user="root"
mysql_password=""

# Read MySQL password from stdin if empty
if [ -z "${mysql_password}" ]; then
  echo -n "Enter MySQL ${mysql_user} password: "
  read -s mysql_password
  echo
fi

# Check MySQL password
echo exit | mysql --user=${mysql_user} --password=${mysql_password} -B 2>/dev/null
if [ "$?" -gt 0 ]; then
  echo "MySQL ${mysql_user} password incorrect"
  exit 1
else
  echo "MySQL ${mysql_user} password correct."
fi

# Create backup directory and set permissions
backup_date=`date +%Y_%m_%d_%H_%M`
backup_dir="${backup_parent_dir}/${backup_date}"
echo "Backup directory: ${backup_dir}"
mkdir -p "${backup_dir}"
chmod 700 "${backup_dir}"

# Get MySQL databases
mysql_databases=`echo 'show databases' | mysql --user=${mysql_user} --password=${mysql_password} -B | sed /^Database$/d`

# Backup and compress each database
for database in $mysql_databases
do
  if [ "${database}" == "information_schema" ] || [ "${database}" == "performance_schema" ]; then
        additional_mysqldump_params="--skip-lock-tables"
  else
        additional_mysqldump_params=""
  fi
  echo "Creating backup of \"${database}\" database"
  mysqldump ${additional_mysqldump_params} --user=${mysql_user} --password=${mysql_password} ${database} | gzip > "${backup_dir}/${database}.gz"
  chmod 600 "${backup_dir}/${database}.gz"
done
